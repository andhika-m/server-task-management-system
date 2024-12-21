<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if ($user = User::where('email', $credentials['email'])->first()) {
            if (Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'token' => $user->createToken('myAppToken')->plainTextToken,
                    'user' => new UserResource($user),
                ]);
            }
        }
        return response()->json([
            'message' => 'Invalid credentials.',
        ], 401);
    }
}
