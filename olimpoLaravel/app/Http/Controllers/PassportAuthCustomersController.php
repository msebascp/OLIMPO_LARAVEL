<?php

    namespace App\Http\Controllers;

    use App\Models\Customer;
    use App\Models\Payment;
    use Cassandra\Date;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\App;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Storage;
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
                ]);
            } catch (ValidationException $e) {
                $errors = $e->validator->getMessageBag();
                $errorMessages = [];

                if ($errors->has('name')) {
                    $errorMessages['name'] = 'El nombre es requerido.';
                }

                if ($errors->has('email')) {
                    $errorMessages['email'] = 'El correo electrónico es inválido o ya ha sido registrado.';
                }

                if ($errors->has('surname')) {
                    $errorMessages['surname'] = 'El apellido es requerido.';
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $errorMessages
                ], 422);
            }
            $data['trainer_id'] = $request->trainer_id;
            $data['password'] = Hash::make('password');
            $data['typeTraining'] = $request->typeTraining;
            $data['dateInscription'] = today();
            $data['nextPayment'] = today()->addMonth();
            $customer = Customer::create($data);
            $payment = new Payment();
            $payment->payment_type = 'Efectivo';
            $payment->payment_date = today();
            $payment->paid = true;
            $payment->customer_id = $customer->id;
            $payment->save();
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
            $trainer->photo = Storage::url($trainer->photo);
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

        public function pay(Request $request) {
            $id = $request->id;
            $customer = Customer::findOrFail($id);
            if ($customer->nextPayment < today()) {
                $customer->nextPayment = today()->addMonth();
            } else {
                $customer->nextPayment = Carbon::parse($customer->nextPayment)->addMonth();
            }
            $payment = new Payment();
            $payment->payment_type = 'Efectivo';
            $payment->payment_date = today();
            $payment->paid = true;
            $payment->customer_id = $customer->id;
            $payment->save();
            $customer->save();
            return response()->json([
                "success" => true,
                "message" => "Pago realizado correctamente",
            ]);
        }

        public function customerEditAccount(Request $request){
            $customer = Auth::user();
            $customer->name = $request->name;
            $customer->surname = $request->surname;
            $customer->typeTraining = $request->typeTraining;
            $customer->email = $request->email;
            $customer->trainer_id = $request->trainer_id;
            $customer->save();
    
            $response = [
                'success' => true,
                'message' => "Cliente editado correctamente"
            ];
            return response()->json($response);
    
    
        }
    }
