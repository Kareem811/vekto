<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $isFirstUser = User::count() == 0;
        $role = $isFirstUser ? 'superadmin' : 'user';
        $registeredUser = User::create([
            'name' => $request->name,
            'password' => $request->password,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $role,
        ]);
        return Response::json(['message' => "Registered Successfully", "user" => $registeredUser], 200);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return Response::json(["message" => "Invalid credentials"], 401);
        }
        $token = $user->createToken('token')->plainTextToken;
        return Response::json([
            "message" => "Login successful",
            "user" => $user,
            "token" => $token
        ], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "message" => "Logged out successfully"
        ], 200);
    }
    public function logoutFromAllDevices(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            "message" => "Logged out from all devices"
        ], 200);
    }
}
