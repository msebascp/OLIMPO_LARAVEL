<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function getAll(Request $request)
    {
        $information = Information::all(); // <- Hay que importar el modelo
        $response = [
            'success' => true,
            'message' => "Información obtenida correctamente",
            'data' => $information
        ];
        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        $information = Information::findOrFail($id);
        if ($information) {
            $information->instagram = $request->instagram;
            $information->facebook = $request->facebook;
            $information->horario1 = $request->horario1;
            $information->horario2 = $request->horario2;
            $information->save();
            $response = [
                'success' => true,
                'message' => "Información modificada correctamente",
            ];
            return response()->json($response);
        }
    }
}
