<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
        if ($entrenador) {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'surname' => 'required|string',
                'email' => 'required|string',
                'specialty' => 'string'
            ]);
            $entrenador->name = $validatedData['name'];
            $entrenador->surname = $validatedData['surname'];
            $entrenador->email = $validatedData['email'];
            $entrenador->specialty = $validatedData['specialty'];
            if ($request->hasFile('photo')) {
                $image = $request->file('photo');
    
                // Valida la imagen
                $request->validate([
                    'photo' => 'image|max:4048', // máx 4 MB
                ]);
    
                // Elimina la imagen antigua si existe
                if ($entrenador->photo) {
                    Storage::delete($entrenador->photo);
                }
    
                // Guarda la imagen nueva y guarda su nombre en la base de datos
                $imagePath = $image->store('public/trainerPhoto');
                $entrenador->photo = $imagePath;
            }
            $entrenador->save();
            $response = [
                'success' => true,
                'message' => "Entrenador modificado correctamente",
            ];
            return response()->json($response);
        }
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
