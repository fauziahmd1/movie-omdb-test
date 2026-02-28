<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if ($request->username === 'aldmic' && $request->password === '123abc123') {
            Session::put('is_login', true);
            return redirect('/');
        }

        return back()->with('error', 'Username or password is incorrect');
    }

    public function logout()
    {
        Session::forget('is_login');
        return redirect('/login');
    }
}
