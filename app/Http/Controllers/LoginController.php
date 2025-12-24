<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');


        if (Auth::attempt($credentials)) {
            if (auth()->user()->role == 'admin') {
                return redirect()->route('dashboard-analytics');
            }
            return redirect('/user/dashboard');
        }


        return back()->with('error', 'Login gagal');
    }


    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
