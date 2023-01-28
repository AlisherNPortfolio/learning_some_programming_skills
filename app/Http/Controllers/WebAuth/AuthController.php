<?php

namespace App\Http\Controllers\WebAuth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PDOException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!Auth::guest()) {
            return redirect()->intended('dashboard');
        }

        if ($request->isMethod('get')) {
            return view('web-auth.login');
        }

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email topilmadi'
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        if (!Auth::guest()) {
            return redirect()->intended('dashboard');
        }

        if ($request->isMethod('get')) {
            return view('web-auth.register');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        try {
            User::query()->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return redirect()->intended();
        } catch (PDOException $e) {
            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->intended();
    }

    public function dashboard()
    {
        return view('web-auth.dashboard');
    }
}
