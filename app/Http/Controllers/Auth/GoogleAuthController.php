<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google authentication page
     */
   public function redirect()
{
    // Simply redirect to Google OAuth
    return Socialite::driver('google')->redirect();
}

    /**
     * Handle Google callback
     */
    public function callback()
    {
        try {
            // Retrieve and verify the Google user via Socialite (uses Google's ID token verification under the hood)
            $googleUser = Socialite::driver('google')->user();

            Log::info('Google User Data:', [
                'id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
            ]);

            $email = strtolower(trim($googleUser->getEmail() ?? ''));

            // Require a verified email from Google when available
            $rawUser = $googleUser->user ?? [];
            $isEmailVerified = $rawUser['email_verified'] ?? $rawUser['verified_email'] ?? null;
            if ($isEmailVerified !== null && !$isEmailVerified) {
                return redirect()->route('voter.login')->withErrors([
                    'email' => 'Your Google email address is not verified. Please verify it in Google first.',
                ]);
            }

            // Enforce campus domain
            if (!$this->isValidCampusEmail($email)) {
                return redirect()->route('voter.login')
                    ->withErrors([
                        'email' => 'Please use your campus email address (@' . env('ALLOWED_CAMPUS_DOMAIN', 'ssct.edu.ph') . ').',
                    ]);
            }

            // Find or create voter (server-side only, ignore any client-provided auth data)
            $voter = $this->findOrCreateVoter($googleUser);

            Log::info('Voter created/found:', [
                'voter_id' => $voter->id,
                'email' => $voter->email,
            ]);

            Auth::guard('voter')->login($voter, true);

            Log::info('Voter authenticated via Google', [
                'voter_id' => $voter->id,
                'email' => $voter->campus_email,
            ]);

            return redirect()->route('voter.dashboard')
                ->with('success', 'Welcome back, ' . $voter->name . '!');
        } catch (Exception $e) {
            Log::error('Google authentication error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('voter.login')
                ->withErrors([
                    'error' => 'Authentication failed. Please try again.',
                ]);
        }
    }

    /**
     * Validate if email belongs to campus domain
     */
    private function isValidCampusEmail($email)
    {
        $allowedDomain = strtolower(env('ALLOWED_CAMPUS_DOMAIN', 'ssct.edu.ph'));

        return is_string($email) && str_ends_with(strtolower($email), '@' . $allowedDomain);
    }

    /**
     * Find existing voter or create new one
     */
    private function findOrCreateVoter($googleUser)
    {
        // Try to find by Google ID first
        $voter = Voter::where('google_id', $googleUser->getId())->first();

        if ($voter) {
            // Update voter information
            $voter->update([
                'name' => $googleUser->getName(),
                'avatar' => $googleUser->getAvatar(),
                'is_verified' => true,
            ]);

            return $voter;
        }

        // Try to find by campus email
        $voter = Voter::where('email', $googleUser->getEmail())
            ->orWhere('campus_email', $googleUser->getEmail())
            ->first();

        if ($voter) {
            // Link Google account to existing voter
            $voter->update([
                'google_id' => $googleUser->getId(),
                'campus_email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(),
                'is_verified' => true,
            ]);

            return $voter;
        }

        // Create new voter
        return Voter::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'campus_email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
            'is_verified' => true,
            'course' => 'To be updated', // Default value, voter can update later
        ]);
    }

    /**
     * Logout voter (this is different from the one in VoterAuthController)
     */
    public function logout()
    {
        Auth::guard('voter')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('voter.login')
            ->with('success', 'You have been logged out successfully.');
    }
}