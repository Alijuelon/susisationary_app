<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Regenerate session untuk keamanan
        $request->session()->regenerate();

        // Cek apakah user aktif — jika tidak, logout dan tolak akses
        if (!Auth::user()->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.']);
        }

        $url = "/"; // Default fallback url

        if (Auth::user()->role === "admin") {
            $url = "admin/dashboard";
        } elseif (Auth::user()->role === "kasir") {
            $url = "kasir/dashboard";
        } elseif (Auth::user()->role === "pelanggan") {
            $url = "pelanggan/dashboard";
        }

        // Redirect ke intended URL (atau URL default berdasarkan role)
        return redirect()->intended($url);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
