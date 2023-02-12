<?php

    namespace App\Http\Controllers;

    use App\Models\Customer;
    use App\Models\Trainer;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;

    class PassportAuthCustomersController extends Controller
    {
        public function register(Request $request)
        {
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required | email:rfc | unique:owners',
                'password' => 'required',
            ]);
            $data['password'] = Hash::make($data['password']);
            $user = new Customer($data);
            $user->save();
            return response()->json([
                "success" => true,
                "message" => "Usuario registrado",
                "data" => [
                    $user
                ]
            ]);
        }

        public function login(Request $request)
        {
            if (Auth::check()) {
                return response()->json([
                    "success" => false,
                    "message" => "El cliente ya está logueado",
                    "data" => [
                        "isTrainer" => false,
                        "isLogin" => true
                    ]
                ]);
            }
            $request->validate([
                'email' => 'required',
                'password' => 'required'
            ]);
            $customer = Customer::where('email', '=', $request->email)->first();
            if (empty($customer)) {
                return response()->json([
                    "success " => false,
                    "message" => "El usuario no existe",
                    "data" => []
                ], 401);
            } elseif (!Hash::check($request->password, $customer->password)) {
                return response()->json([
                    "success" => false,
                    "message" => "Contraseña incorrecta",
                    "data" => []
                ], 401);
            }
            $token = $customer->createToken("myToken")->accessToken;
            return response()->json([
                "success" => true,
                "message" => "El usuario se ha logueado",
                "data" => [
                    "isTrainer" => false,
                    "isLogin" => true,
                    "token" => $token
                ]
            ]);
        }

        public function logout(Request $request)
        {
            $user = Auth::user();
            $user->token()->revoke();
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
                    Auth::user()
                ]
            ]);
        }

        public function isLogin() {
            if (Auth::check()) {
                return response()->json([
                    "success" => true,
                    "message" => 'El cliente está logueado',
                    "data" => [
                        "token" => "",
                        "isTrainer" => false,
                        "isLogin" => true
                    ]
                ]);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => 'El cliente no está logueado',
                    "data" => [
                        "token" => "",
                        "isTrainer" => false,
                        "isLogin" => false
                    ]
                ]);
            }
        }
    }
