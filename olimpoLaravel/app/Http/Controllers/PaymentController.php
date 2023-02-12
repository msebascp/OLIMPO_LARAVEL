<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function getAll(Request $request) {
        $payments = Payment::all(); // <- Hay que importar el modelo
        $response = [
            'success' => true,
            'message' => "Pagos obtenidos correctamente",
            'data' => $payments
        ];
        return response()->json($response);
    }

    public function getById(Request $request, $id) {
        $payment = Payment::findOrFail($id);
        $response = [
            'success' => true,
            'message' => "Pago con id: " . $id . " obtenido",
            'data' => $payment
        ];
        return response()->json($response);
    }

    public function create(Request $request) {
        Payment::insert($request->validate([
            'payment_type' => 'required|string',
            'payment_date' => 'required|string',
            'paid' => 'required|boolean'
        ]));
        $response = [
            'success' => true,
            'message' => "Pago creado correctamente"
        ];
        return response()->json($response);
    }

    public function delete(Request $request, $id) {
        DB::table('payments')->where('id', $id)->delete();
        $response = [
            'success' => true,
            'message' => "Pago borrado correctamente",
        ];
        return response()->json($response);
    }

    public function update(Request $request, $id) {
        $payment = Payment::findOrFail($id);
        $payment->payment_type = $request->payment_type;
        $payment->payment_date = $request->payment_date;
        $payment->paid = $request->paid;
        $payment->save();
        $response = [
            'success' => true,
            'message' => "Pago modificado correctamente",
        ];
        return response()->json($response);
    }

    public function customers(Request $request, $id) {
        $payment = Payment::find($id);
        $payment->customer;
        $response = [
            'success' => true,
            'message' => "Pago con id: " . $id . " realizado por el cliente con el id: " . $payment->customer->id,
            'Inscripcion' => $payment
        ];
        return response()->json($response);
    }
}
