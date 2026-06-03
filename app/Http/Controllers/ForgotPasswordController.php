<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    // STEP 1: tampilkan form
    public function showForm()
    {
        return view('pages.forgot-password');
    }

    // STEP 1: kirim OTP (HASHED)
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.'
            ]);
        }

        $otpPlain = rand(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($otpPlain),
                'created_at' => now()
            ]
        );

        Mail::raw(
            "Kode OTP reset password BatikAI Anda adalah: {$otpPlain}",
            function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Reset Password BatikAI');
            }
        );

        return back()
            ->with('email', $request->email)
            ->with('step', 'step2');
    }

    // STEP 2: verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->withErrors([
                'otp' => 'OTP tidak ditemukan'
            ]);
        }

        // ❗ cek expired 10 menit
        if (now()->diffInMinutes($record->created_at) > 10) {
            return back()->withErrors([
                'otp' => 'OTP sudah kadaluarsa'
            ]);
        }

        // ❗ cek hash OTP
        if (!Hash::check($request->otp, $record->token)) {
            return back()->withErrors([
                'otp' => 'OTP salah'
            ]);
        }

        session([
            'reset_email' => $request->email,
            'otp_verified' => true
        ]);

        return back()->with('step', 'step3');
    }

    // STEP 3: reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        if (!session('otp_verified')) {
            return redirect()->route('password.request');
        }

        $email = session('reset_email');

        User::where('email', $email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();

        session()->forget(['reset_email', 'otp_verified']);

        return redirect('/masuk')
            ->with('success', 'Password berhasil diubah');
    }
}