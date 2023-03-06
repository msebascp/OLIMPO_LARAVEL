<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\ClientRepository;

class PassportAuthTrainersController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required|email:rfc|unique:trainers',
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
        $data['password'] = Hash::make('password');
        $data['specialty'] = $request->specialty;
        $trainer = Trainer::create($data);
        return response()->json([
            "success" => true,
            "message" => "Usuario registrado",
            "data" => [
                $trainer
            ]
        ]);
    }

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
                "message" => "El correo no corresponde a ningún entrenador",
                "data" => []
            ], 401);
        } elseif (!Hash::check($request->password, $trainer->password)) {
            return response()->json([
                "success" => false,
                "message" => "Contraseña incorrecta",
                "data" => []
            ], 401);
        }
        App::clearResolvedInstance(ClientRepository::class);
        app()->singleton(ClientRepository::class, function () {
            return new ClientRepository(15, 'GRp1YCgt07NCJdLGCNbojmKWwSAd4uiXHT2K7wOq'); // You should give the client id in the first parameter
        });
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
        $trainer = Auth::guard('api-trainers')->user();
        $trainer->photo = Storage::url($trainer->photo);
        return response()->json([
            "success" => true,
            "message" => "Datos de usuario: ",
            "data" => $trainer

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

    public function trainerEditAccount(Request $request){
        $trainer = Auth::guard("api-trainers")->user();
        $trainer->name = $request->name;
        $trainer->surname = $request->surname;
        $trainer->email = $request->email;
        $trainer->specialty = $request->specialty;
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');

            // Valida la imagen
            $validated = $request->validate([
                'photo' => 'required|image|max:4048', // máx 4 MB
            ]);

            // Elimina la imagen antigua si existe
            if ($trainer->photo) {
                Storage::delete($trainer->photo);
            }

            // Guarda la imagen nueva y guarda su nombre en la base de datos
            $imagePath = $image->store('public/trainerPhoto');
            $trainer->photo = $imagePath;
        }
        $trainer->save();

        $response = [
            'success' => true,
            'message' => "Entrenador editado correctamente"
        ];
        return response()->json($response);
    }

    public function endPointTrainer(Request $request){
        $user = Auth::guard("api-trainers")->user();
        $user->tokens()->delete();
        $response = [
            'success' => true,
            'message' => "Se ha cerrado sesión en todos los dispositivos correctamente"
        ];
        return response()->json($response);

    }
}
