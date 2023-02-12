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
            $trainer = false;
            if (Auth::check()) {
                return response()->json([
                    "success" => false,
                    "message" => "El usuario ya está logueado",
                    "data" => [
                        "token" => "",
                        "isTrainer" => false,
                        "isLogin" => true
                    ]
                ]);
            }
            $request->validate([
                'email' => 'required',
                'password' => 'required'
            ]);
            $user = Customer::where('email', '=', $request->email)->first();
            if (empty($user)) {
                $user = Trainer::where('email', '=', $request->email)->first();
                $trainer = true;
            }
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
                    "isTrainer" => $trainer,
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
                "message" => "Cierre de sesión correcto"
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
            $isLogin = false;
            $message = '';
            if (Auth::check()) {
                $isLogin = true;
                $message = 'Cliente logueado';
            } elseif (Auth::guard('api-trainers')->check()) {
                $isLogin = true;
                $message = 'Admin logueado';
            }
            return response()->json([
                "success" => $isLogin,
                "message" => $message,
                "data" => [
                    "token" => "",
                    "isTrainer" => false,
                    "isLogin" => $isLogin
                ]
            ]);
        }

        public function whoIam(Request $request)
        {
            if (Auth::user()) {
                return response()->json([
                    "success" => true,
                    "message" => "Cliente",
                    "data" => [
                        "token" => "",
                        "isTrainer" => false,
                        "isLogin" => true
                    ]
                ]);
            } elseif (Auth::guard('api-trainer') ->user()) {
                return response()->json([
                    "success" => true,
                    "message" => "Admin",
                    "data" => [
                        "token" => "",
                        "isTrainer" => true,
                        "isLogin" => true
                    ]
                ]);
            }
            return response()->json([
                "success" => false,
                "message" => "No logueado",
                "data" => [
                    "token" => "",
                    "isTrainer" => false,
                    "isLogin" => false
                ]
            ]);
        }
    }
