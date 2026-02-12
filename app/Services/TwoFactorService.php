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
        
        if (strlen($code) !== 6 || !ctype_digit($code)) {
            \Log::error('2FA Verification Failed: Invalid code format', [
                'user_id' => $user->id,
                'code_length' => strlen($code),
                'code' => $code
            ]);
            return false;
        }

        try {
            // Get the current timestamp
            $timestamp = $this->google2fa->getTimestamp();
            
            // Verify with a window of 4 (allows for clock skew of ±2 minutes)
            // Window of 4 means ±2 time steps (each step is 30 seconds)
            // This accounts for server time differences and user device clock drift
            $verified = $this->google2fa->verifyKey($user->two_factor_secret, $code, 4, $timestamp);
            
            if (!$verified) {
                // Try with a larger window as fallback
                $verified = $this->google2fa->verifyKey($user->two_factor_secret, $code, 8, $timestamp);
            }
            
            if (!$verified) {
                \Log::warning('2FA Verification Failed: Code mismatch', [
                    'user_id' => $user->id,
                    'code' => $code,
                    'secret_length' => strlen($user->two_factor_secret),
                    'secret_preview' => substr($user->two_factor_secret, 0, 10) . '...'
                ]);
            }
            
            return $verified;
        } catch (\Exception $e) {
            \Log::error('2FA Verification Exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'code' => $code,
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

