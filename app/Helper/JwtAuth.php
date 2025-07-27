<?php

namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuth
{

    public static function createToken($aud, $hour = 1, $id = null, $user = null,$role='user')
    {
        $payload = [
            'iss' => $_SERVER['SERVER_NAME'],
            'aud' => $aud,
            'iat' => time(),
            'nbf' => time() + 10,
            'exp' => time() + ($hour * 3600),
            'id'  => $id,
            'user' => $user,
            'role' => $role
        ];

        return JWT::encode($payload, env('JWT_KEY'), 'HS256');
    }

    public static function decodeToken($token)
    {
        JWT::$leeway = 50;
        try {
            $data = JWT::decode($token, new Key(env('JWT_KEY'), 'HS256'));
            return $data;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function verifyToken($token = "token", $active = true)
    {
        if ($active) {
            $headers =  getallheaders();
            try {
                $xTOKEN  = $_COOKIE[$token] ?? $headers[$token];
                $data = self::decodeToken($xTOKEN);
                return $data;
            } catch (\Throwable $th) {
                return false;
            }
        } else {
            try {
                $data = self::decodeToken($token);
                return $data;
            } catch (\Throwable $th) {
                return false;
            }
        }
    }

    public static function getToken($key, $token = "token", $active = true)
    {

        if ($active) {
            $headers =  getallheaders();
            try {
                $xTOKEN  = $_COOKIE[$token] ?? $headers[$token];
                $data = self::decodeToken($xTOKEN);
                return $data->$key;
            } catch (\Throwable $th) {
                return false;
            }
        } else {
            try {
                $data = self::decodeToken($token);
                return $data->$key;
            } catch (\Throwable $th) {
                return false;
            }
        }
    }
}
