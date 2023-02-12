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
        if (Auth::guard('api-trainers')->check()) {
            return response()->json([
                "success" => false,
                "message" => "El entrenador ya está logueado",
                "data" => [
                    "isTrainer" => true,
                    "isLogin" => true
                ]
            ]);
        }
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $trainer = Trainer::where('email', '=', $request->email)->first();
        if (empty($trainer)) {
            return response()->json([
                "success " => false,
                "message" => "El entrenador no existe",
                "data" => []
            ], 401);
        } elseif (!Hash::check($request->password, $trainer->password)) {
            return response()->json([
                "success" => false,
                "message" => "Contraseña incorrecta",
                "data" => []
            ], 401);
        }
        $token = $trainer->createToken("trainer-token")->accessToken;
        return response()->json([
            "success" => true,
            "message" => "El entrenador se ha logueado",
            "data" => [
                "isTrainer" => true,
                "isLogin" => true,
                "token" => $token
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $trainer = Auth::guard('api-trainers')->user();
        $trainer->token()->revoke();
        return response()->json([
            "success" => true,
            "message" => "Cierre de sesión correcto",
            "data" => [
                "isLogin" => false
            ]
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            "success" => true,
            "message" => "Datos de usuario: ",
            "data" => [
                "Entrenador" => Auth::guard('api-trainers')->user()
            ]
        ]);
    }

    public function isLogin() {
        if (Auth::guard('api-trainers')->check()) {
            return response()->json([
                "success" => true,
                "message" => 'El entrenador está logueado',
                "data" => [
                    "token" => "",
                    "isTrainer" => true,
                    "isLogin" => true
                ]
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => 'El entrenador no está logueado',
                "data" => [
                    "token" => "",
                    "isTrainer" => false,
                    "isLogin" => false
                ]
            ]);
        }
    }
}
