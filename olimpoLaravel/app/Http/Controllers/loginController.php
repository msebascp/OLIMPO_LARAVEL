<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function login(Request $request) {
        //[email => awdmaiw@gmail.com, password => 21312]
        $data = $request->validate([
            'email' => 'required|email:rfc',
            'password' => 'required'
        ]);

        if (Auth::attempt($data)) {
            // una vez el login se ha completado con el attempt
            // el usuario (instancia de User) queda almacenado en la clase Auth
            // Al tener "HasApiTokens" tiene acceso a más metodos, como "createToken"
            return Auth::user()->createToken("token");
        }

        return 'Ususario no logeado F';
    }

    /**
     * Solo se puede llamar si el usuario esta logeado
     * Devolverá un mensaje con el nombre del usuario logeado
      */

    public function whoAmI(Request $request) {
        return Auth::user();
    }
}
