<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function register()
    {
        return view('auth.registrasi');
    }
    public function registerFunc(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8',
            'bio' => 'nullable',
            'avatar' => 'nullable',
            'konfirmasiPassword' => 'required|min:8'
        ]);
        $data['password'] = Hash::make($data['password']);
        if ($data['konfirmasiPassword'] != $request->input('password')) {
            return redirect()->back()->with('error', 'Konfirmasi Password Gagal')->withInput();
        }
        User::create($data);
        return redirect()->route('login');
    }
    public function funcLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
