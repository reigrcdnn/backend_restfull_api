<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api', [
    //         'except'    => [
    //             'login',
    //             'register'
    //         ]
    //     ]);
    // }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Anda salah'
            ], 401);
        }

        // $user = auth()->user();
        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api')->user(),
            'token'   => $token
        ], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|between:2,100',
            'email'     => 'required|string|email|max:100|unique:users',
            'password'  => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password)
        ]);

        if ($user) {
            return response()->json([
                'succes'    => true,
                'user'      => $user,
            ], 201);
        }
        return response()->json([
            'success' => false,
        ], 409);
    }

    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        $hapusToken = JWTAuth::invalidate(JWTAuth::getToken());

        if ($hapusToken) {
            return response()->json([
                'success' => true,
                'message' => 'Logout Berhasil',
            ]);
        }
    }
}
