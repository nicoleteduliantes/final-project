<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminLoginController extends Controller
{
   public function adminLogin(Request $request)
{
    $credentials = $request->validate([
        'id'   => 'required|integer',
        'password' => 'required',
        'type'     => 'required|in:osa,org'
    ]);

    $guard = $credentials['type'];
    $authData = [
    ($guard === 'osa' ? 'osa_id' : 'org_id') => $credentials ['id'],
    'password' => $credentials ['password']
    ];

    if (Auth::guard($guard)->attempt ($authData)){
        $request-> session()->regenerate();

        return back() ->withErrors([
            'id' => 'The provided credentials do not match our records.',
        ]);
    }
    
}
}
