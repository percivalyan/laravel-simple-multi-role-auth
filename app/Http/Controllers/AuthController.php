<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            Session::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'action' => 'login',
                'description' => 'User logged in.',
                'payload' => json_encode(['email' => $request->email]),
                'last_activity' => Carbon::now()->timestamp,
            ]);

            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Login failed']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        // Cek apakah email sudah digunakan
        if (User::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'Email is already registered. Please use another email.'])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Logging setelah user berhasil dibuat
        Session::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => 'register',
            'description' => 'User registered.',
            'payload' => json_encode(['email' => $request->email]),
            'last_activity' => Carbon::now()->timestamp,
        ]);

        return redirect('/login')->with('success', 'Registration successful! Please login.');
    }

    public function logout(Request $request)
    {
        // Optional: log aktivitas logout
        if (Auth::check()) {
            Session::create([
                'id' => Str::uuid(),
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'action' => 'logout',
                'description' => 'User logged out.',
                'payload' => null,
                'last_activity' => Carbon::now()->timestamp,
            ]);
        }

        Auth::logout();
        return redirect('/login');
    }
}
