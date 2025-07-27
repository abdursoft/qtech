<?php
namespace App\Http\Controllers\Auth;

use App\Helper\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * user basic signin
     */
    public function signin(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email'    => 'string|email|required',
            'password' => 'string|required|min:4',
        ]);

        if ($validation->fails()) {
            return response()->json(
                [
                    'status'  => 'fail',
                    'message' => "Authentication fail",
                    "errors"  => $validation->errors(),
                ],
                401
            );
        }

        $user = User::where('email', '=', $request->input('email'))->first();
        if ($user) {
            if (password_verify($request->input('password'), $user->password)) {
                $token = JWTAuth::createToken('auth', 1, $user->id, $user->username,$user->role);
                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Login successful',
                    'token'     => $token,
                    'role'      => $user->role,
                    'tokenType' => 'Bearer',
                    'TokenAge'  => '3600 seconds',
                ], 200);
            } else {
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'Incorrect password',
                ], 400);
            }
        } else {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Login failed',
            ], 400);
        }
    }

    /**
     * user basic signup
     */
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name"     => "required|string",
            "email"    => "required|unique:users,email",
            "password" => "required|string|min:4",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Customer registration failed',
                'errors'  => $validator->errors(),
            ], 400);
        }

        try {
            User::create(
                [
                    "name"     => $request->input('name'),
                    "email"    => $request->input('email'),
                    "password" => password_hash($request->input('password'), PASSWORD_DEFAULT),
                ]
            );
            return response([
                'status'  => 'success',
                'message' => 'Customer registration successful',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => "fail",
                'message' => 'Customer registration failed',
                'errors'  => $th->getMessage(),
            ], 400);
        }
    }
}
