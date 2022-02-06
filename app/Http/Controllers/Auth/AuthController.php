<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api')->except(['login', 'register']);
    }

    public function register(Request $request) {

        $data = $request->only('name', 'email', 'password');

        $validator = Validator::make($data, [
            'name' => ['required'],
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        if ($user) {

            $info = [
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => $user
            ];

            return response()->json($info, 201);

        } else {

            $info = [
                'status' => 'error',
                'message' => 'Unable to create user'
            ];

            return response()->json($info, 400);

        }

    }

    public function login(Request $request) {

        $data = $request->only('email', 'password');

        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $token = auth()->attempt($validator->validated());

        // dd($token);
        if(!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $info = [
            'status' => 'success',
            'message' => 'Logged in successfully'
        ];

        return $this->createNewToken($token, $info);

    }

    protected function createNewToken($token, $info){

        $info = array_merge($info, [
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => auth()->user()
            ]
        ]);

        return response()->json($info,200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {

        $info = [
            'status' => 'success',
            'message' => 'Token refreshed successfully'
        ];
        return $this->createNewToken(auth()->refresh(), $info);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        $info = [
            'status' => 'success',
            'data' => [
                'user' => auth()->user()
            ]
        ];
        return response()->json($info, 200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out'], 200);
    }
}
