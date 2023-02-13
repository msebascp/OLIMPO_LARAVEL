<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainerController extends Controller
{
    public function getAll(Request $request) {
        $entrenadores = Trainer::all(); // <- Hay que importar el modelo
        $response = [
            'success' => true,
            'message' => "Entrenadores obtenidos correctamente",
            'data' => $entrenadores
        ];
        return response()->json($response);
    }

    public function getById(Request $request, $id) {
        $entrenador = Trainer::findOrFail($id);
        $response = [
            'success' => true,
            'message' => "Entrenador con id: " . $id . " obtenido",
            'data' => $entrenador
        ];
        return response()->json($response);
    }

    public function create(Request $request) {
        Trainer::insert($request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|string|unique:clientes',
            'phone' => 'string|unique:clientes',
            'specialty' => 'string'
        ]));
        $response = [
            'success' => true,
            'message' => "Entrenador creado correctamente"
        ];
        return response()->json($response);
    }

    public function delete(Request $request, $id) {
        DB::table('trainers')->where('id', $id)->delete();
        $response = [
            'success' => true,
            'message' => "Entrenador borrado correctamente",
        ];
        return response()->json($response);
    }

    public function update(Request $request, $id) {
        $entrenador = Trainer::findOrFail($id);
        $entrenador->name = $request->name;
        $entrenador->surname = $request->surname;
        $entrenador->password = $request->password;
        $entrenador->email = $request->email;
        $entrenador->phone = $request->phone;
        $entrenador->specialty = $request->specialty;
        $entrenador->save();
        $response = [
            'success' => true,
            'message' => "Entrenador modificado correctamente",
        ];
        return response()->json($response);
    }

    public function getCustomer(Request $request, $id) {
        $entrenador = Trainer::find($id);
        $entrenador->customer;
        $response = [
            'success' => true,
            'message' => "Entrenamiento con id: " . $id . " tiene estos clientes" ,
            'data' => $entrenador
        ];
        return response()->json($response);
    }
}
