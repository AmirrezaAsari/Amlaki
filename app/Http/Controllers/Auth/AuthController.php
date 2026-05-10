<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $jwt;

    public function __construct(JwtService $jwt)
    {
        $this->jwt = $jwt;
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'type' => 'user'
        ]);

        $token = $this->jwt->generateToken($user);

        return response()->json([
            'token'=>$token,
            'user'=>$user
        ]);
    }

    public function login(Request $request)
    {
        $user = User::where('email',$request->email)->first();

        if (!$user || !Hash::check($request->password,$user->password)) {
            return response()->json([
                'message'=>'Invalid credentials'
            ],401);
        }

        $token = $this->jwt->generateToken($user);

        return response()->json([
            'token'=>$token,
            'user'=>$user
        ]);
    }
}
