<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ( ! Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status'  => false,
                'message' => 'Email & Password does not match with our record.',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status'  => true,
            'message' => 'User Logged In Successfully',
            'token'   => $user->createToken("API TOKEN")->plainTextToken,
        ], 200);

    }
}
