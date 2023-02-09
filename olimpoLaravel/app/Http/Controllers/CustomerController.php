<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function getAll(Request $request) {
        $clientes = Customer::all(); // <- Hay que importar el modelo
        $response = [
            'success' => true,
            'message' => "Clientes obtenidos correctamente",
            'data' => $clientes
        ];
        return response()->json($response);
    }

    public function getById(Request $request, $id) {
        $cliente = Customer::findOrFail($id);
        $response = [
            'success' => true,
            'message' => "Cliente con id: " . $id . " obtenido",
            'data' => $cliente
        ];
        return response()->json($response);
    }

    public function create(Request $request) {
        Customer::insert($request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|string|unique:clientes',
            'phone' => 'string|unique:clientes',
        ]));
        $response = [
            'success' => true,
            'message' => "Cliente creado correctamente"
        ];
        return response()->json($response);
    }

    public function delete(Request $request, $id) {
        DB::table('customers')->where('id', $id)->delete();
        $response = [
            'success' => true,
            'message' => "Cliente borrado correctamente",
        ];
        return response()->json($response);
    }

    public function update(Request $request, $id) {
        $cliente = Customer::findOrFail($id);
        $cliente->name = $request->name;
        $cliente->surname = $request->surname;
        $cliente->typeTraining = $request->typeTraining;
        $cliente->password = Hash::make($request->password);
        $cliente->email = $request->email;
        $cliente->phone = $request->phone;
        $cliente->trainer_id = $request->trainer_id;
        $cliente->save();
        $response = [
            'success' => true,
            'message' => "Cliente modificado correctamente",
        ];
        return response()->json($response);
    }

    public function payments(Request $request, $id) {
        $cliente = Customer::find($id);
        $cliente->payment;
        $response = [
            'success' => true,
            'message' => "Cliente con id: " . $id,
            'data' => $cliente
        ];
        return response()->json($response);
    }

    public function trainers(Request $request, $id) {
        $cliente = Customer::find($id);
        $cliente->trainer;
        $response = [
            'success' => true,
            'message' => "Cliente con id: " . $id . " tiene al entrenador con id: " . $cliente->trainer->id,
            'data' => $cliente
        ];
        return response()->json($response);
    }

    public function search(Request $request) {
        $input = $request->only(['name']);
        $query = DB::table('customers');

        if (!empty($input['name']['value'])) {
            $query->where('name', 'like', '%' . $input['name']['value'] . '%');
        }
        $limit = $request->input('limit', 100);
        $query->limit($limit);
    
        return response()->json($query->get());
    }
    
    
}
