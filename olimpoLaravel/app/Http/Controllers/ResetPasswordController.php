<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Trainer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    public function forgetPassword(Request $request)
    {
        $email = $request->email;
        $customer = Customer::where('email', $request->email)->first();
        if (empty($customer)) {
            return response()->json([
                "success" => false,
                "message" => "El correo no corresponde a ningún usuario",
            ]);
        }
        $token = Str::random(60);
        try {
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token
            ]);
            Mail::send('resetPassword', ['token'=>$token], function ($message) use($email){
                $message->to($email);
                $message->subject('Reset Your Password');
            });
            return response()->json([
                "success" => true,
                "message" => 'Correo electrónico para cambiar la contraseña enviado',
            ]);
        } catch (Exception $exception) {
            return response()->json([
                "success" => false,
                "message" => $exception->getMessage(),
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string',
            'password' => 'required|string',
        ]);

        $token = $request->token;
        $passwordRest = DB::table('password_resets')->where('token', $token)->first();

        // Verificando si el token es correcto
        if (!$passwordRest) {
            return response()->json([
                'success' => false,
                'message' => 'El correo no está asociado a ningún usuario'
            ]);
        }

        // Validando fecha del token
        /**if (!$passwordRest->created_at > now()) {
            return response(['message' => 'Token has expired.'], 200);
        }*/

        $customer = Customer::where('email', $passwordRest->email)->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'El correo no está asociado a ningún cliente'
            ]);
        }

        $customer->password = Hash::make($request->password);
        $customer->save();

        DB::table('password_resets')->where('token', $token)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contraseña cambiada correctamente'
        ]);
    }

    public function forgetPasswordTrainer(Request $request)
    {
        $email = $request->email;
        $trainer = Trainer::where('email', $request->email)->first();
        if (empty($trainer)) {
            return response()->json([
                "success" => false,
                "message" => "El correo no corresponde a ningún usuario",
            ]);
        }
        $token = Str::random(60);
        try {
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token
            ]);
            Mail::send('resetPasswordTrainer', ['token'=>$token], function ($message) use($email){
                $message->to($email);
                $message->subject('Reset Your Password');
            });
            return response()->json([
                "success" => true,
                "message" => 'Correo electrónico para cambiar la contraseña enviado',
            ]);
        } catch (Exception $exception) {
            return response()->json([
                "success" => false,
                "message" => $exception->getMessage(),
            ]);
        }
    }

    public function resetPasswordTrainer(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string',
            'password' => 'required|string',
        ]);

        $token = $request->token;
        $passwordRest = DB::table('password_resets')->where('token', $token)->first();

        // Verificando si el token es correcto
        if (!$passwordRest) {
            return response()->json([
                'success' => false,
                'message' => 'Token caducado o erróneo'
            ]);
        }

        // Validando fecha del token
        /**if (!$passwordRest->created_at > now()) {
        return response(['message' => 'Token has expired.'], 200);
        }*/

        $trainer = Trainer::where('email', $passwordRest->email)->first();

        if (!$trainer) {
            return response()->json([
                'success' => false,
                'message' => 'El correo no está asociado a ningún entrenador'
            ]);
        }

        $trainer->password = Hash::make($request->password);
        $trainer->save();

        DB::table('password_resets')->where('token', $token)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contraseña cambiada correctamente'
        ]);
    }

    public function changePassword(Request $request)
    {
        try {
            $password = $request->validate([
               'password' => 'required'
            ]);
        } catch (ValidationException $e) {
            return response()->json() ([
               'success' => false,
               'message' => $e
            ]);
        }
        $customer = Auth::user();
        $customer->password = Hash::make($password['password']);
        $customer->save();
        return response()->json([
            'success' => true,
            'message' => 'Contraseña cambiada correctamente'
        ]);
    }

    public function changePasswordTrainer(Request $request)
    {
        try {
            $password = $request->validate([
                'password' => 'required'
            ]);
        } catch (ValidationException $e) {
            return response()->json() ([
                'success' => false,
                'message' => $e
            ]);
        }
        $trainer = Auth::guard('api-trainers')->user();
        $trainer->password = Hash::make($password['password']);
        $trainer->save();
        return response()->json([
            'success' => true,
            'message' => 'Contraseña cambiada correctamente'
        ]);
    }
}
