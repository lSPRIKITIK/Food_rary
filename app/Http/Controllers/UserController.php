<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request) {
        $data = $request->validate([
            'username' => 'required|max:35',
            'password' => 'required|max:35'
        ]);

        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard'); 
        }
        return back()->withErrors([
            'username' => 'Invalid Username or Password.',
        ]);
    }

    public function logout (Request $request) {
        Auth::Logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');

    }
}