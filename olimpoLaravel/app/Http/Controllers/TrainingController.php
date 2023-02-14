<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function saveTraining(Request $request)
    {
        $pdfFile = $request->file('pdfTraining');
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $pdfFile);

        if ($mimeType != 'application/pdf') {
            return response()->json([
                'success' => false,
                'message' => 'El archivo subido no es un PDF'
            ], 400);
        }

        $path = $pdfFile->store('public/training');
        $training = new Training;
        $training->pdfTraining = $path;
        $training->name = $request->input('name');
        $training->id_customer = $request->input('id_customer');
        $training->save();

        $response = [
            'success' => true,
            'message' => "Entrenamiento guardado correctamente"
        ];
        return response()->json($response);
    }


    public function getCustomer(Request $request, $id)
    {
        $training = Training::find($id);
        $customer = $training->customer;
        $response = [
            'success' => true,
            'message' => "Cliente con id: " . $id . " tiene estos entrenamientos",
            'Entrenamiento' => $customer
        ];
        return response()->json($response);
    }
}