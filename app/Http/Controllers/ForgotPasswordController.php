<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        $user = Buyer::where('email', $email)->first();

        if (! $user) {
            $user = Vendor::where('email', $email)->first();
        }

        if (! $user) {
            return redirect()->back()->with('fail', 'Email not found');
        }

        $otp = random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(15);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $otp,
                'created_at' => Carbon::now(),
                'expires_at' => $expiresAt,
            ]
        );

        Mail::send('emails.forgot-password-otp', ['name' => $user->name, 'otp' => $otp, 'expiresAt' => $expiresAt], function ($message) use ($email) {
            $message->to($email)
                ->subject('Your password reset OTP');
        });

        $request->session()->put('password_reset_email', $email);

        return redirect()->route('forgot.password.verify')->with('success', 'OTP sent to your email.');
    }

    public function showVerifyForm()
    {
        return view('auth.forgot-password-verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $email = $request->session()->get('password_reset_email');

        if (! $email) {
            return redirect()->route('forgot.password')->with('fail', 'Session expired. Please start over.');
        }

        $otp = $request->input('otp');

        $tokenRow = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $otp)
            ->first();

        if (! $tokenRow) {
            return redirect()->back()->with('fail', 'Invalid OTP. Please check and try again.')->withInput();
        }

        if (Carbon::now()->greaterThan(Carbon::parse($tokenRow->expires_at))) {
            return redirect()->back()->with('fail', 'OTP has expired. Please request a new one.')->withInput();
        }

        $request->session()->put('password_reset_verified', true);

        return redirect()->route('forgot.password.reset')->with('success', 'OTP verified. You can now reset your password.');
    }

    public function showResetForm()
    {
        if (!session()->has('password_reset_verified')) {
            return redirect()->route('forgot.password.verify')->with('fail', 'Please verify OTP first');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = $request->session()->get('password_reset_email');
        $verified = $request->session()->get('password_reset_verified');

        if (! $email || ! $verified) {
            return redirect()->route('forgot.password')->with('fail', 'Please verify your OTP before resetting your password.');
        }

        $tokenRow = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (! $tokenRow || Carbon::now()->greaterThan(Carbon::parse($tokenRow->expires_at))) {
            return redirect()->route('forgot.password')->with('fail', 'OTP has expired. Please request a new password reset.');
        }

        $user = Buyer::where('email', $email)->first();

        if (! $user) {
            $user = Vendor::where('email', $email)->first();
        }

        if (! $user) {
            return redirect()->route('forgot.password')->with('fail', 'No account found for this email.');
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        DB::table('password_reset_tokens')->where('email', $email)->delete();
        $request->session()->forget(['password_reset_email', 'password_reset_verified']);

        $loginRoute = $user instanceof Buyer ? 'buyers.loginForm' : 'vendors.loginForm';

        return redirect()->route($loginRoute)->with('success', 'Your password has been reset. You may now log in.');
    }
}
