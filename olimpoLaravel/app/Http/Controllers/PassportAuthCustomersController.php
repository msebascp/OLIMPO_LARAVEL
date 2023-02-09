<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PassportAuthCustomersController extends Controller
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
            'email' => 'required | email:rfc',
            'password' => 'required'
        ]);
        $user = Customer::where('email', '=', $request->email)->first();
        if (empty($user)) {
            return response()->json([
                "success " => false,
                "message" => "El usuario no existe"
            ], 401);
        } elseif (!Hash::check($request->password, $user->password)) {
            return response()->json([
                "success" => false,
                "message" => "Contraseña incorrecta"
            ], 401);
        }
        $user->api_token = $user->createToken("token")->plainTextToken;
        $user->save();
        return response()->json([
            "success" => true,
            "message" => "El usuario se ha logueado",
            "data" => [
                "token" => $user->api_token
            ]
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            "success" => true,
            "message" => "Datos de usuario: ",
            "data" => [
                Auth::user()
            ]
        ]);
    }
}
