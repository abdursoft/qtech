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
    public function signin(Request $request){
        $validation = Validator::make($request->all(),[
            'username' => 'string|required',
            'password' => 'string|required|min:4'
        ]);

        if($validation->fails()){
            return response()->json(
                [
                    'status' => 'fail',
                    'message' => "Authentication fail",
                    "errors" => $validation->errors()
                ],
                401
            );
        }

        $user = User::where('username', '=', $request->input('username'))->first();
        if($user){
            if(password_verify($request->input('password'), $user->password)){
                $token = JWTAuth::createToken('user',1,$user->id,$user->username);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'token' => $token
                ],200)->cookie("user_token",$token,60,'/');
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Incorrect password'
                ],400);
            }
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'Login failed'
            ],400);
        }
    }

    /**
     * user basic signup
     */
    public function signup(Request $request){
        $validator = Validator::make($request->all(), [
            "username" => "required|unique:users,username",
            "password" => "required|string|min:4"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'message' => 'User registration failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            User::create(
                [
                    "username" => $request->input('username'),
                    "password" => password_hash($request->input('password'), PASSWORD_DEFAULT)
                ]
            );
            return response([
                'status' => 'success',
                'message' => 'User registration successful'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => "fail",
                'message' => 'User registration failed',
                'errors' => $th->getMessage()
            ], 400);
        }
    }

}
