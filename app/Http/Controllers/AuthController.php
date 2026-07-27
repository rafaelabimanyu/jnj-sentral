<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Memproses login pengguna.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Kredensial yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Mengarahkan pengguna berdasarkan peran (role).
     */
    protected function redirectBasedOnRole($user)
    {
        if ($user->role === 'owner') {
            return redirect()->intended(route('owner.dashboard'));
        } elseif ($user->role === 'admin_ops') {
            return redirect()->intended(route('admin_ops.dashboard'));
        } elseif ($user->role === 'admin_web') {
            return redirect()->intended(route('admin_web.dashboard'));
        }

        Auth::logout();
        return redirect()->route('login')->with('error', 'Akses peran tidak dikenal.');
    }
}
