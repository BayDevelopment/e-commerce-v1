<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Login | Fashion & Lifestyle',
        ];
        return view('pages.login', $data);
    }
    public function loginProses(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->withInput();
        }

        $user = Auth::user();

        $request->session()->regenerate();

        if (is_null($user->email_verified_at)) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun belum diverifikasi. Silakan cek email Anda.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Redirect Berdasarkan Role
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {
            return redirect('/admin')
                ->with('success', 'Login berhasil sebagai Admin 👋');
        }

        return redirect()->route('customer.dashboard')
            ->with('success', 'Login berhasil. Selamat datang kembali 👋');
    }




    // REGISTER
    public function register()
    {
        $data = [
            'title' => 'Register | Fashion & Lifestyle',
        ];
        return view('pages.register', $data);
    }
    public function RegisterProses(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer', // 🔥 tambahin ini
        ]);

        // kirim email verifikasi
        $user->sendEmailVerificationNotification();

        return redirect()->route('login')
            ->with('success', 'Link verifikasi telah dikirim ke email Anda.');
    }


    // LOGOUT
    public function logoutProses(Request $request)
    {
        Auth::logout();

        // invalidate session
        $request->session()->invalidate();

        // regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda berhasil logout.');
    }
}
