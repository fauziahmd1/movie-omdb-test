<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if ($username === 'aldmic' && $password === '123abc123') {
            session(['logged_in' => true]);
            return redirect()->route('movies.index');
        } else {
            return back()->withErrors(['msg' => __('messages.login_error')]);
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login.show');
    }
}
