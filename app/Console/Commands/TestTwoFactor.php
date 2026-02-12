<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\TwoFactorService;
use PragmaRX\Google2FA\Google2FA;

class TestTwoFactor extends Command
{
    protected $signature = '2fa:test {email} {code}';
    protected $description = 'Test 2FA verification for a user';

    public function handle(TwoFactorService $service)
    {
        $email = $this->argument('email');
        $code = $this->argument('code');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User not found: {$email}");
            return 1;
        }
        
        $this->info("Testing 2FA for user: {$user->email}");
        $this->info("Secret exists: " . ($user->two_factor_secret ? 'YES' : 'NO'));
        
        if ($user->two_factor_secret) {
            $this->info("Secret length: " . strlen($user->two_factor_secret));
            $this->info("Secret preview: " . substr($user->two_factor_secret, 0, 20) . '...');
        }
        
        $this->info("Code to verify: {$code}");
        $this->info("Code length: " . strlen($code));
        
        // Test direct verification
        $google2fa = new Google2FA();
        $this->info("\nTesting direct verification:");
        
        try {
            $result1 = $google2fa->verifyKey($user->two_factor_secret, $code, 8);
            $this->info("verifyKey with window 8: " . ($result1 ? 'PASS' : 'FAIL'));
            
            $result2 = $google2fa->verifyKey($user->two_factor_secret, $code, 4);
            $this->info("verifyKey with window 4: " . ($result2 ? 'PASS' : 'FAIL'));
            
            $result3 = $google2fa->verifyKey($user->two_factor_secret, $code, 2);
            $this->info("verifyKey with window 2: " . ($result3 ? 'PASS' : 'FAIL'));
            
            $result4 = $google2fa->verifyKey($user->two_factor_secret, $code);
            $this->info("verifyKey with no window: " . ($result4 ? 'PASS' : 'FAIL'));
        } catch (\Exception $e) {
            $this->error("Exception: " . $e->getMessage());
        }
        
        // Test via service
        $this->info("\nTesting via TwoFactorService:");
        $result = $service->verify($user, $code);
        $this->info("Service verify result: " . ($result ? 'PASS' : 'FAIL'));
        
        return 0;
    }
}
