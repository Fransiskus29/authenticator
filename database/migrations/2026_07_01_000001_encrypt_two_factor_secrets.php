<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Widen for encrypted payload
        Schema::table('two_factor_accounts', function (Blueprint $table) {
            $table->text('secret')->change();
        });

        // Encrypt existing plaintext rows in place.
        // Skip rows that already look encrypted (Crypt payloads are JSON-base64).
        DB::table('two_factor_accounts')->orderBy('id')->each(function ($row) {
            try {
                Crypt::decryptString($row->secret); // already encrypted? skip
                return;
            } catch (\Throwable $e) {
                // plaintext — encrypt it
            }
            DB::table('two_factor_accounts')
                ->where('id', $row->id)
                ->update(['secret' => Crypt::encryptString($row->secret)]);
        });
    }

    public function down(): void
    {
        // No-op: we won't decrypt back to plaintext on rollback.
    }
};
