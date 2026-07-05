<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;


class AuthController extends Controller
{
   // REGISTER USER
public function register(Request $request)
{
    $request->validate([
        'first_name' => 'required',
        'last_name'  => 'required',
        'email'      => 'required|email|unique:users,email',
        'password'   => 'required|min:6|confirmed',
    ], [
        'first_name.required' => 'Nama depan wajib diisi.',
        'last_name.required'  => 'Nama belakang wajib diisi.',
        'email.required'      => 'Email wajib diisi.',
        'email.email'         => 'Format email tidak valid.',
        'email.unique'        => 'Email sudah dipakai.',
        'password.required'   => 'Kata sandi wajib diisi.',
        'password.min'        => 'Kata sandi minimal 6 karakter.',
        'password.confirmed'  => 'Konfirmasi kata sandi tidak sesuai.',
    ]);

    $user = User::create([
        'first_name' => $request->first_name,
        'last_name'  => $request->last_name,
        'email'      => $request->email,
        'password'   => Hash::make($request->password),
        'role'       => 'user',
    ]);

    // Auto login setelah register
    Auth::login($user);

    return redirect()->route('dashboard');
}

    /// LOGIN
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ], [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Kata sandi wajib diisi.',
    ]);

    if (Auth::attempt($credentials)) {

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    return back()->withErrors([
        'email' => 'Maaf, email atau kata sandi Anda salah.',
    ])->withInput();
}
public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/masuk');
}
public function redirectToGoogle()
{
    return Socialite::driver('google')->redirect();
}
public function handleGoogleCallback()
{
    $googleUser = Socialite::driver('google')->user();

    $names = explode(' ', $googleUser->name, 2);

    $user = User::where('email', $googleUser->email)->first();

    if (!$user) {

        $user = User::create([
            'first_name' => $names[0] ?? '',
            'last_name'  => $names[1] ?? '',
            'email'      => $googleUser->email,
            'google_id'  => $googleUser->id,
            'password'   => Hash::make(Str::random(40)),
            'role'       => 'user',
        ]);

    } else {

        if (!$user->google_id) {
            $user->google_id = $googleUser->id;
            $user->save();
        }
    }

    Auth::login($user);

return $user->hasRole('admin')
    ? redirect()->route('admin.dashboard')
    : redirect()->route('user.dashboard');
}
}