<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('bbc.index')->with('success', 'Tizimga kirdingiz.');
        }

        return back()->withErrors(['email' => 'Email yoki parol noto\'g\'ri.']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $roles = ['user', 'admin', 'creator', 'editor'];
        $role = $roles[array_rand($roles)];


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $role,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('bbc.index')->with('success', 'Ro\'yxatdan o\'tdingiz va tizimga kirdingiz.');
    }

    public function logout()
    {
        Auth::logout();
        return back();
    }
}
