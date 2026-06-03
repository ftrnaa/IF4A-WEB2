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
        'email'      => 'required|email|unique:users',
        'password'   => 'required|min:6|confirmed',
    ]);

    $user = User::create([
        'first_name' => $request->first_name,
        'last_name'  => $request->last_name,
        'email'      => $request->email,
        'password'   => Hash::make($request->password),
        'role'       => 'user',
    ]);

    // auto login setelah register
    Auth::login($user);

    return redirect()->route('user.dashboard');
}

    // LOGIN
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // login menggunakan session laravel
    if (Auth::attempt($credentials)) {

        $request->session()->regenerate();

        $user = Auth::user();

        // redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
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

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('user.dashboard');
}
}