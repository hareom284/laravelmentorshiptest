<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginApiRequest;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends Controller
{
    public function login(LoginApiRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $USER_AGENT =  substr($request->userAgent() ?? '', 0, 255);
                $token = $user->createToken($USER_AGENT)->plainTextToken;

                return response()->json([
                    "message" => "success",
                    "data" => [
                        "token" => $token,
                        "user_data" => $user
                    ]
                ], 200);
            } else {
                return response()->json([
                    "message" => "Invalid Creditional",
                    "data" => ""
                ], 401);
            }
        } catch (\Exception $error) {
            return response()->json([
                "message" => "Something was wrong",
                "data" => ""
            ], 422);
        }
    }
}
