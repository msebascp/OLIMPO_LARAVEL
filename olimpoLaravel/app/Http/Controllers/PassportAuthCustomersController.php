<?php

    namespace App\Http\Controllers;

    use App\Models\Customer;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\App;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Validation\ValidationException;
    use Laravel\Passport\ClientRepository;

    class PassportAuthCustomersController extends Controller
    {
        public function register(Request $request): JsonResponse
        {
            try {
                $data = $request->validate([
                    'name' => 'required',
                    'email' => 'required|email:rfc|unique:customers',
                    'surname' => 'required',
                ], [
                    'required' => ':attribute es requerido',
                    'email' => ':attribute debe ser una dirección de correo electrónico válida',
                    'unique' => ':attribute ya ha sido registrado'
                ]);
            } catch (ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors()
                ], 422);
            }
            $customer = new Customer($data);
            $customer->typeTraining = $request->typeTraining;
            $customer->trainer_id = $request->trainer_id;
            $customer->password = Hash::make('password');
            $customer->dateInscription = today();
            $customer->lastPayment = today();
            $customer->nextPayment = today()->addMonth();
            $customer->save();
            return response()->json([
                "success" => true,
                "message" => "Usuario registrado",
                "data" => [
                    $customer
                ]
            ]);
        }

        public function login(Request $request): JsonResponse
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
                    "message" => "El correo no corresponde a ningún usuario",
                    "data" => []
                ], 401);
            } elseif (!Hash::check($request->password, $customer->password)) {
                return response()->json([
                    "success" => false,
                    "message" => "Contraseña incorrecta",
                    "data" => []
                ], 401);
            }
            App::clearResolvedInstance(ClientRepository::class);
            app()->singleton(ClientRepository::class, function () {
                return new ClientRepository(13, 'SgK9gniC50uIwvebBP7D0qNaeqDM9EnKcInpw8Dn'); // You should give the client id in the first parameter
            });
            $token = $customer->createToken("client-Token")->accessToken;
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

        public function logout(Request $request): JsonResponse
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

        public function me(Request $request): JsonResponse
        {
            return response()->json([
                "success" => true,
                "message" => "Datos de usuario: ",
                "data" => [
                    Auth::user()
                ]
            ]);
        }

        public function isLogin(): JsonResponse
        {
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

        public function getTrainer(Request $request): JsonResponse {
            $customer = Auth::user();
            $trainer = $customer->trainer;
            $response = [
                'success' => true,
                'message' => "Cliente tiene al entrenador",
                'data' => $trainer
            ];
            return response()->json($response);
        }

        public function getAllTrainings(Request $request): JsonResponse
        {
            $customer = Auth::user();
            $trainings = $customer->trainings;
            $response = [
                'success' => true,
                'message' => "Cliente tiene estos entrenamientos",
                'data' => $trainings
            ];
            return response()->json($response);
        }
    }
