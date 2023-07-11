<?php

namespace App\Http\Controllers;

use App\Models\ImcRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImcRecordController extends Controller
{
    public function create(Request $request) {
        $data = $request->validate([
            'weight' => 'required|integer',
            'height' => 'required|integer',
            'imc' => 'required|integer'
        ]);
        $data['customer_id'] = $request->customer_id;
        $data['weighing_date'] = today();
        $imcRecord = ImcRecord::create($data);
        $response = [
            'success' => true,
            'message' => "Calculo de IMC creado correctamente",
            'data' => $imcRecord
        ];
        return response()->json($response);
    }

    public function delete(Request $request, $id)
    {
        DB::table('imc_records')->where('customer_id', $id)->delete();
        $response = [
            'success' => true,
            'message' => "Historial borrado correctamente",
        ];
        return response()->json($response);
    }

    public function getAll(Request $request) {
        $imcRecords = ImcRecord::all(); // <- Hay que importar el modelo
        $response = [
            'success' => true,
            'message' => "Historial de IMCs obtenidos correctamente",
            'data' => $imcRecords
        ];
        return response()->json($response);
    }
    public function customers(Request $request, $id) {
        $imcRecord = ImcRecord::find($id);
        $imcRecord->customer;
        $response = [
            'success' => true,
            'message' => "Historial del cliente obtenido",
            'Inscripcion' => $imcRecord
        ];
        return response()->json($response);
    }
}
