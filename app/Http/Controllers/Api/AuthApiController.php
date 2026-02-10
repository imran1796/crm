<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                "message" => "Invalid credentials"
            ], 401);
        }

        $token = auth()->user()->createToken('api-token')->plainTextToken;

        return response()->json([
            "message" => "Login successful",
            "token" => $token,
            "user" => auth()->user()
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(["message" => "Logged out"]);
    }
}
