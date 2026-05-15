<?php

namespace App\Http\Controllers;

use App\Models\Osa;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/*==============
   ADMIN LOGIN 
  ============== */
class AdminLoginController extends Controller
{
   public function adminLogin(Request $request)
{
    $credentials = $request->validate([
        'id'       => 'required|integer',
        'password' => 'required',
        'type'     => 'required|in:osa,org'
    ]);

    $guard = $credentials['type'];
    $idField = ($guard === 'osa' ? 'osa_id' : 'org_id');
    
    $authData = [
        $idField   => $credentials['id'],
        'password' => $credentials['password']
    ];

/* LOGIN AUTHENTICATION */
    if (Auth::guard($guard)->attempt($authData)) {
        $user = Auth::guard($guard)->user();

/* GENERATE TOKEN */
        $token = $user->createToken('admin_token', [$guard])->plainTextToken;

/* RETURN JSON*/
    return response()->json([
            'status' => 'success',
            'user'   => $user,
            'token'  => $token,
            'role'   => $guard 
        ], 200);
    }

    return response()->json([
        'status'  => 'error',
        'message' => 'The provided credentials do not match our records.'
    ], 401);
}
}
