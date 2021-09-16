<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
        ]);

        $password = Hash::make($request->password);

        $user = User::create([
            'email' => $request->email,
            'password' => $password
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambah',
            'data' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $password = Hash::check($request->password, $user->password);
            if ($password) {
                $token = bin2hex(random_bytes(40));
                $user->update([
                    'token' => $token
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Anda berhasil login',
                    'data' => $user
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'password salah',
                    'data' => $user
                ], 401);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'username salah',
                'data' => $user
            ], 401);
        }
    }
}
