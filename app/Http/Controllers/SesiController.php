<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesiController extends Controller
{
    // Menampilkan form login
    public function index()
    {
        if (Auth::check()) {
            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect('/admin');
            } elseif ($role === 'operator') {
                return redirect('/operator');
            } else {
                Auth::logout();
                return redirect('/')->withErrors('Role tidak dikenali.');
            }
        }

        return view('login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($infologin)) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/admin');
            } elseif ($user->role === 'operator') {
                return redirect()->intended('/operator');
            } else {
                Auth::logout(); // Jika role tidak dikenali
                return redirect('/')->withErrors('Role tidak dikenali.');
            }
        } else {
            return redirect('/')->withErrors('Username dan Password tidak sesuai')->withInput();
        }
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
