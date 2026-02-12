<?php

namespace App\Services;

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Collection;

class TwoFactorService
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate secret key for user
     */
    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Generate QR code URL
     */
    public function getQRCodeUrl(User $user, string $secret): string
    {
        $companyName = config('app.name', 'FxEngne');
        $companyEmail = $user->email;
        
        return $this->google2fa->getQRCodeUrl(
            $companyName,
            $companyEmail,
            $secret
        );
    }

    /**
     * Verify the 2FA code
     */
    public function verify(User $user, string $code): bool
    {
        if (!$user->two_factor_secret) {
            \Log::error('2FA Verification Failed: No secret found for user', ['user_id' => $user->id]);
            return false;
        }

        // Trim and ensure code is exactly 6 digits
        $code = trim($code);
        $code = preg_replace('/[^0-9]/', '', $code); // Remove any non-numeric characters
        
        if (strlen($code) !== 6 || !ctype_digit($code)) {
            \Log::error('2FA Verification Failed: Invalid code format', [
                'user_id' => $user->id,
                'code_length' => strlen($code),
                'code' => $code
            ]);
            return false;
        }

        try {
            // Ensure secret is uppercase (base32 should be uppercase)
            $secret = strtoupper(trim($user->two_factor_secret));
            
            // Verify with a window of 8 (allows for clock skew of ±4 minutes)
            // Window parameter: checks current time step ± window steps
            // Each time step is 30 seconds, so window of 8 = ±4 minutes
            // The verifyKey method signature: verifyKey($secret, $key, $window = null, $timestamp = null)
            $verified = $this->google2fa->verifyKey($secret, $code, 8);
            
            if (!$verified) {
                // Try with window of 4 as fallback
                $verified = $this->google2fa->verifyKey($secret, $code, 4);
            }
            
            if (!$verified) {
                // Try with window of 2 as fallback
                $verified = $this->google2fa->verifyKey($secret, $code, 2);
            }
            
            if (!$verified) {
                // Try with window of 1 (default) as last fallback
                $verified = $this->google2fa->verifyKey($secret, $code, 1);
            }
            
            if (!$verified) {
                \Log::warning('2FA Verification Failed: Code mismatch', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'code' => $code,
                    'secret_length' => strlen($secret),
                    'secret_preview' => substr($secret, 0, 10) . '...',
                    'timestamp' => time(),
                    'server_time' => now()->toDateTimeString(),
                    'secret_original' => substr($user->two_factor_secret, 0, 10) . '...'
                ]);
            } else {
                \Log::info('2FA Verification Success', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            }
            
            return $verified;
        } catch (\Exception $e) {
            \Log::error('2FA Verification Exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'code' => $code,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Generate recovery codes
     */
    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = $this->generateRecoveryCode();
        }
        return $codes;
    }

    /**
     * Generate a single recovery code
     */
    protected function generateRecoveryCode(): string
    {
        return strtoupper(bin2hex(random_bytes(4))) . '-' . strtoupper(bin2hex(random_bytes(4)));
    }

    /**
     * Verify recovery code
     */
    public function verifyRecoveryCode(User $user, string $code): bool
    {
        $recoveryCodes = $user->getRecoveryCodes();
        
        if (!in_array($code, $recoveryCodes)) {
            return false;
        }

        // Remove used recovery code
        $recoveryCodes = array_values(array_diff($recoveryCodes, [$code]));
        $user->two_factor_recovery_codes = $recoveryCodes;
        $user->save();

        return true;
    }
}

