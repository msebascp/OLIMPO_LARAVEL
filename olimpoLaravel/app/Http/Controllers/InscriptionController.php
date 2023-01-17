<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InscriptionController extends Controller
{
    public function getAll(Request $request) {
        $inscripciones = Inscription::all(); // <- Hay que importar el modelo
        $response = [
            'success' => true,
            'message' => "Inscripciones obtenidas correctamente",
            'data' => $inscripciones
        ];
        return response()->json($response);
    }

    public function getById(Request $request, $id) {
        $inscripcion = Inscription::findOrFail($id);
        $response = [
            'success' => true,
            'message' => "Inscripción con id: " . $id . " obtenida",
            'data' => $inscripcion
        ];
        return response()->json($response);
    }

    public function create(Request $request) {
        Inscription::insert($request->validate([
            'registration_date' => 'required|string',
            'payment_type' => 'required|string',
        ]));
        $response = [
            'success' => true,
            'message' => "Inscripción creada correctamente"
        ];
        return response()->json($response);
    }

    public function delete(Request $request, $id) {
        DB::table('inscriptions')->where('id', $id)->delete();
        $response = [
            'success' => true,
            'message' => "Inscripción borrada correctamente",
        ];
        return response()->json($response);
    }

    public function update(Request $request, $id) {
        $inscripcion = Inscription::findOrFail($id);
        $inscripcion->registration_date = $request->registration_date;
        $inscripcion->payment_type = $request->payment_type;
        $inscripcion->save();
        $response = [
            'success' => true,
            'message' => "Inscripción modificada correctamente",
        ];
        return response()->json($response);
    }

    public function customers(Request $request, $id) {
        $inscripcion = Inscription::find($id);
        $inscripcion->customer;
        $response = [
            'success' => true,
            'message' => "Inscripcion con id: " . $id . " tiene al cliente con id: " . $inscripcion->customer->id,
            'Inscripcion' => $inscripcion
        ];
        return response()->json($response);
    }
}
