<?php

namespace App\Console\Commands;

use App\Models\TwoFactorAccount;
use Illuminate\Console\Command;

class PurgeDeletedAccounts extends Command
{
    protected $signature = 'authenticator:purge';
    protected $description = 'Permanently delete 2FA accounts that were soft-deleted more than 7 days ago';

    public function handle(): int
    {
        $deleted = TwoFactorAccount::onlyTrashed()
            ->where('deleted_at', '<=', now()->subDays(7))
            ->forceDelete();

        $this->info("Purged {$deleted} permanently deleted account(s).");
        return Command::SUCCESS;
    }
}
