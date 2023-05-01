<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'unauthorized']]);
    }

    public function unauthorized() {
        return response()->json(['error'=>'Não autorizado', 401]);
    }

    public function login(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');

        $token = auth()->attempt([
            'email' => $email,
            'password' => $password
        ]);

        if (!$token) {
            return response()->json(['error'=>'E-mail e/ou senha errados!', 404]);
        }

        return response()->json(['token' => $token,'user' => auth()->user()], 200);
    }

    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'asd'], 200);
    }

    public function refresh() {
        
    }
}
