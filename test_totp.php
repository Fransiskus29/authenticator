<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$g = new PragmaRX\Google2FA\Google2FA();

$accounts = App\Models\TwoFactorAccount::all();
echo "Total accounts: " . $accounts->count() . "\n";

foreach ($accounts as $a) {
    echo "\n--- Account: {$a->label} ---\n";
    echo "Secret: " . $a->secret . "\n";
    echo "Secret length: " . strlen($a->secret) . "\n";
    echo "Secret base32: " . (preg_match('/^[A-Z2-7=]+$/', $a->secret) ? 'YES' : 'NO') . "\n";

    $otp = $g->getCurrentOtp($a->secret);
    echo "Generated OTP: " . $otp . "\n";

    $valid = $g->verifyKey($a->secret, $otp);
    echo "Self-verify: " . ($valid ? 'PASS' : 'FAIL') . "\n";
    echo "Time: " . date('Y-m-d H:i:s') . "\n";
    echo "Timestamp: " . time() . "\n";
}
