<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TwoFactorController extends Controller
{
    protected $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * Show 2FA setup page
     */
    public function showSetup()
    {
        $user = Auth::user();
        
        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.security')->with('error', '2FA is already enabled');
        }

        // Generate secret if not exists
        if (!$user->two_factor_secret) {
            $user->two_factor_secret = $this->twoFactorService->generateSecretKey();
            $user->save();
        }

        $qrCodeUrl = $this->twoFactorService->getQRCodeUrl($user, $user->two_factor_secret);

        return view('auth.two-factor.setup', [
            'secret' => $user->two_factor_secret,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    /**
     * Enable 2FA after verification
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        if (!$user->two_factor_secret) {
            return back()->withErrors(['code' => '2FA setup not initiated']);
        }

        // Verify the code
        if (!$this->twoFactorService->verify($user, $request->code)) {
            return back()->withErrors(['code' => 'Invalid verification code']);
        }

        // Enable 2FA
        $user->two_factor_confirmed_at = now();
        $user->two_factor_recovery_codes = $this->twoFactorService->generateRecoveryCodes();
        $user->save();

        return redirect()->route('auth.two-factor.recovery-codes')
            ->with('success', 'Two-factor authentication has been enabled');
    }

    /**
     * Show recovery codes
     */
    public function showRecoveryCodes()
    {
        $user = Auth::user();
        
        if (!$user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.security');
        }

        return view('auth.two-factor.recovery-codes', [
            'recoveryCodes' => $user->getRecoveryCodes(),
        ]);
    }

    /**
     * Regenerate recovery codes
     */
    public function regenerateRecoveryCodes()
    {
        $user = Auth::user();
        
        if (!$user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.security');
        }

        $user->two_factor_recovery_codes = $this->twoFactorService->generateRecoveryCodes();
        $user->save();

        return redirect()->route('auth.two-factor.recovery-codes')
            ->with('success', 'Recovery codes have been regenerated');
    }

    /**
     * Download recovery codes as text file
     */
    public function downloadRecoveryCodes()
    {
        $user = Auth::user();
        
        if (!$user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.security');
        }

        $recoveryCodes = $user->getRecoveryCodes();
        $filename = 'fxengne-recovery-codes-' . date('Y-m-d') . '.txt';
        
        $content = "FxEngne - Two-Factor Authentication Recovery Codes\n";
        $content .= "==================================================\n\n";
        $content .= "Account: " . $user->email . "\n";
        $content .= "Generated: " . now()->format('Y-m-d H:i:s') . "\n\n";
        $content .= "IMPORTANT: Store these codes in a safe place.\n";
        $content .= "Each code can only be used once.\n\n";
        $content .= "Recovery Codes:\n";
        $content .= "---------------\n";
        
        foreach ($recoveryCodes as $index => $code) {
            $content .= ($index + 1) . ". " . $code . "\n";
        }
        
        $content .= "\n\n";
        $content .= "If you lose access to your authenticator device, use one of these codes to sign in.\n";
        $content .= "After using a recovery code, it cannot be used again.\n";

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Invalid password']);
        }

        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return redirect()->route('profile.security')
            ->with('success', 'Two-factor authentication has been disabled');
    }

    /**
     * Show 2FA verification page
     */
    public function showVerification()
    {
        if (!session('two_factor_required')) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.two-factor.verify');
    }

    /**
     * Verify 2FA code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = Auth::user();

        // Try verification code first
        if ($this->twoFactorService->verify($user, $request->code)) {
            session()->forget('two_factor_required');
            session(['two_factor_verified' => true]);
            return redirect()->intended(route('dashboard.index'));
        }

        // Try recovery code
        if ($this->twoFactorService->verifyRecoveryCode($user, $request->code)) {
            session()->forget('two_factor_required');
            session(['two_factor_verified' => true]);
            return redirect()->intended(route('dashboard.index'))
                ->with('success', 'Recovery code used. Please regenerate your recovery codes.');
        }

        return back()->withErrors(['code' => 'Invalid verification code']);
    }
}
