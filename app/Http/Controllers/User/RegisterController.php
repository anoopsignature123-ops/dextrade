<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request): View
    {
        $sponsor = $request->query('sponsor', null);
        $position = strtolower($request->query('position', 'left'));
        if (! in_array($position, ['left', 'right'])) {
            $position = 'left';
        }
        $isLockedSponsor = $request->has('sponsor');
        $isLockedPosition = $request->has('position');

        return view('user.auth.register', compact('sponsor', 'position', 'isLockedSponsor', 'isLockedPosition'));
    }

    /**
     * Live AJAX lookup for sponsor code verification.
     */
    public function checkSponsor(Request $request): JsonResponse
    {
        $code = trim($request->query('code', ''));

        if (empty($code)) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid sponsor code.',
            ]);
        }

        $sponsorUser = User::where('referral_code', $code)->first();

        if ($sponsorUser) {
            return response()->json([
                'success' => true,
                'name' => $sponsorUser->name,
                'email' => $sponsorUser->email,
                'referral_code' => $sponsorUser->referral_code,
            ]);
        }

        // System default admin fallback code
        if ($code === 'DEX-0000001') {
            return response()->json([
                'success' => true,
                'name' => 'Dex Trade System Admin',
                'email' => 'admin@dextrade.com',
                'referral_code' => 'DEX-0000001',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid Sponsor Code! Member not found in system.',
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'sponsor_id' => 'required|string',
            'position' => 'required|in:left,right',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $sponsorCode = trim($request->sponsor_id);
        $sponsorUser = User::where('referral_code', $sponsorCode)->first();

        if (! $sponsorUser && $sponsorCode !== 'DEX-0000001') {
            return redirect()->back()->withInput()->withErrors(['sponsor_id' => 'Invalid Sponsor Code! Member not found in system.']);
        }

        $referralCode = User::generateReferralCode();
        $txPin = (string) rand(100000, 999999);

        // New member account created as inactive by default until package investment
        $user = User::create([
            'role_id' => 2,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'referral_code' => $referralCode,
            'sponsor_code' => $sponsorCode,
            'position' => strtolower($request->position),
            'status' => 'inactive',
            'password' => Hash::make($request->password),
        ]);

        // Send Welcome Email Notification via Database Template System
        send_template_email('welcome-user', $user->email, [
            'name' => $user->name,
            'email' => $user->email,
            'referral_code' => $user->referral_code,
            'sponsor_code' => $user->sponsor_code,
            'position' => strtoupper($user->position),
            'login_url' => route('user.login'),
        ]);

        Auth::login($user);

        $registeredUser = [
            'user_id' => $user->referral_code,
            'sponsor_id' => $user->sponsor_code,
            'position' => strtoupper($user->position),
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'tx_pin' => $txPin,
        ];

        return view('user.auth.register', [
            'sponsor' => $user->sponsor_code,
            'position' => $user->position,
            'isLockedSponsor' => false,
            'isLockedPosition' => false,
            'registeredUser' => $registeredUser,
            'showModal' => true,
        ]);
    }
}
