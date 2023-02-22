<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function getAll(Request $request)
    {
        $clientes = Customer::all(); // <- Hay que importar el modelo
        $response = [
            'success' => true,
            'message' => "Clientes obtenidos correctamente",
            'data' => $clientes
        ];
        return response()->json($response);
    }

    public function getById(Request $request, $id)
    {
        $cliente = Customer::findOrFail($id);
        $response = [
            'success' => true,
            'message' => "Cliente con id: " . $id . " obtenido",
            'data' => $cliente
        ];
        return response()->json($response);
    }

    public function delete(Request $request, $id)
    {
        DB::table('customers')->where('id', $id)->delete();
        $response = [
            'success' => true,
            'message' => "Cliente borrado correctamente",
        ];
        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        $cliente = Customer::findOrFail($id);
        if ($cliente) {
            $cliente->name = $request->name;
            $cliente->surname = $request->surname;
            $cliente->typeTraining = $request->typeTraining;
            $cliente->email = $request->email;
            $cliente->trainer_id = $request->trainer_id;
            if ($request->hasFile('photo')) {
                $image = $request->file('photo');
    
                // Valida la imagen
                $request->validate([
                    'photo' => 'image|max:4048', // máx 4 MB
                ]);
    
                // Elimina la imagen antigua si existe
                if ($cliente->photo) {
                    Storage::delete($cliente->photo);
                }
    
                // Guarda la imagen nueva y guarda su nombre en la base de datos
                $imagePath = $image->store('public/customerPhoto');
                $cliente->photo = $imagePath;
            }
            $cliente->save();
            $response = [
                'success' => true,
                'message' => "Cliente modificado correctamente",
            ];
            return response()->json($response);
        }
    }

    public function payments(Request $request, $id)
    {
        $cliente = Customer::find($id);
        $cliente->payment;
        $response = [
            'success' => true,
            'message' => "Cliente con id: " . $id,
            'data' => $cliente
        ];
        return response()->json($response);
    }

    public function trainers(Request $request, $id)
    {
        $cliente = Customer::find($id);
        $cliente->trainer;
        $response = [
            'success' => true,
            'message' => "Cliente con id: " . $id . " tiene al entrenador con id: " . $cliente->trainer->id,
            'data' => $cliente
        ];
        return response()->json($response);
    }

    public function search(Request $request)
    {
        $input = $request->only(['name']);
        $query = DB::table('customers');

        if (!empty($input['name']['value'])) {
            $query->where('name', 'like', '%' . $input['name']['value'] . '%');
        }
        $limit = $request->input('limit', 100);
        $query->limit($limit);

        return response()->json($query->get());
    }

    public function getTrainings(Request $request, $id)
    {
        $customer = Customer::find($id);
        $trainings = $customer->trainings;
        $response = [
            'success' => true,
            'message' => "Cliente con id: " . $id . " tiene estos entrenamientos",
            'data' => $trainings
        ];
        return response()->json($response);
    }

    public function downloadPDF($filename)
    {
        $file_path = storage_path('app/public/training/' . $filename);

        if (file_exists($file_path)) {
            return response()->download(
                $file_path,
                'entrenamiento.pdf',
                [
                    'Content-Type' => "application/pdf",
                    'Content-Disposition' => "inline"
                ]
            );
        } else {
            $response = [
                'success' => true,
                'message' => "PDF no encontrado"
            ];
            return response()->json($response);
        }
    }

}
