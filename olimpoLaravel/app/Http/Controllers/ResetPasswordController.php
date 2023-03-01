<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function forgetPassword(Request $request)
    {
        $email = $request->email;
        $customer = Customer::where('email', $request->email)->first();
        if (empty($customer)) {
            return response()->json([
                "success " => false,
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
                "success " => true,
                "message" => $token,
            ]);
        } catch (Exception $exception) {
            return response()->json([
                "success " => false,
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
            return response(['message' => 'Token Not Found.'], 200);
        }

        // Validando fecha del token
        /**if (!$passwordRest->created_at > now()) {
            return response(['message' => 'Token has expired.'], 200);
        }*/

        $customer = Customer::where('email', $passwordRest->email)->first();

        if (!$customer) {
            return response(['message' => 'User does not exists.'], 200);
        }

        $customer->password = Hash::make($request->password);
        $customer->save();

        DB::table('password_resets')->where('token', $token)->delete();

        return response(['message' => 'Password Successfully Updated.'], 200);
    }
}
