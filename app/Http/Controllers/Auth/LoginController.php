<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
public function showLoginForm()
{
return view('auth.login');
}

public function login(Request $request)
{
$credentials = $request->validate([
'email' => 'required|email',
'password' => 'required',
]);




if (Auth::attempt($credentials)) {



    if (Auth::user()->role !== 'admin') {
        Auth::logout();
        return back()->withErrors([
            'email' => 'Anda bukan admin',
        ]);
    }
    $request->session()->regenerate();
    return redirect()->intended('/admin/products');
}

return back()->withErrors([
'email' => 'Email atau password salah',
]);
}

public function logout(Request $request)
{
Auth::logout();
$request->session()->invalidate();
$request->session()->regenerateToken();

return redirect('/login');
}
}
