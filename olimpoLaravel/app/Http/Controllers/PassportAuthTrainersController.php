<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PassportAuthTrainersController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::check()) {
            return response()->json([
                "success" => false,
                "message" => "El usuario ya está logueado",
                "data" => [
                    Auth::user()
                ]
            ]);
        }
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $user = Trainer::where('email', '=', $request->email)->first();
        if (empty($user)) {
            return response()->json([
                "success " => false,
                "message" => "El usuario no existe",
                "data" => []
            ], 401);
        } elseif (!Hash::check($request->password, $user->password)) {
            return response()->json([
                "success" => false,
                "message" => "Contraseña incorrecta",
                "data" => []
            ], 401);
        }
        $token = $user->createToken("myToken")->accessToken;
        return response()->json([
            "success" => true,
            "message" => "El usuario se ha logueado",
            "data" => [
                "token" => $token
            ]
        ]);
    }
}
