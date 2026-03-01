<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        return view('pages.reset-password', [
            'token' => $token,
            'title' => 'Reset Password'
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function ($user) use ($request) {

                $user->password = Hash::make($request->password);
                $user->save();
            }
        );

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset.');
    }
}
