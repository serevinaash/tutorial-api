<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Menggunakan Auth::attempt untuk login
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // Generate token setelah login berhasil
            $token = $user->createToken($request->device_name ?? 'default')->plainTextToken;

            return response()->json([
                'message' => 'Login berhasil',
                'token' => $token,
            ], 200);
        }

        // Jika gagal, kirimkan respons gagal
        return response()->json(['message' => 'Email atau password salah'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function me(Request $request)
    {
        $user = Auth::user();
        return response()->json(Auth::user());
    }
}
