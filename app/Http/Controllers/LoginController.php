<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
  public function showLoginForm()
  {
    return view('auth.login');
  }

  public function login(Request $request)
  {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
      return redirect()->intended('/')->with('success', 'Logged in successfully.');
    }
    return redirect('/login')->with('error', 'Invalid Email or Password.');
  }

  public function logout()
  {
    Auth::logout();
    return redirect('/login')->with('success', 'Logged out successfully.');
  }
}
