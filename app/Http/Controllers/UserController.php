<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required|min:5|max:35',
            'password' => 'required|min:8|max:35'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard'); 
        }
        return back()->withErrors([
            'username' => 'Invalid Username or Password.',
        ]);
    }
}