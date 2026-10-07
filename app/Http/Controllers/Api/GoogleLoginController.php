<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleLoginController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // Update existing user with google id
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                ]);
            } else {
                // Not in users table. Check if they applied in applications table
                $application = \Illuminate\Support\Facades\DB::table('applications')->where('email', $googleUser->getEmail())->first();
                
                $frontendUrl = env('FRONTEND_URL', 'https://account.homefinder.id');
                
                if ($application) {
                    // Applied but not approved yet (or rejected)
                    if ($application->status_crm === 'Ditolak') {
                        return redirect()->away($frontendUrl . '/login?error=ApplicationRejected');
                    }
                    return redirect()->away($frontendUrl . '/login?error=WaitingForApproval');
                } else {
                    // Not applied at all
                    return redirect()->away($frontendUrl . '/login?error=NotRegistered');
                }
            }

            // Create Sanctum Token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Redirect back to frontend with token
            $frontendUrl = env('FRONTEND_URL', 'https://account.homefinder.id');
            return redirect()->away($frontendUrl . '/dashboard?token=' . $token . '&role=' . $user->role);
            
        } catch (\Exception $e) {
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
            return redirect()->away($frontendUrl . '/login?error=Google_Login_Failed');
        }
    }
}
