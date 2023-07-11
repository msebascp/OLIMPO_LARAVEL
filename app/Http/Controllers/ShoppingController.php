<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Shopping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShoppingController extends Controller
{

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'id_customer' => 'required',
            'id_product' => 'required',
        ]);

        $product = Shopping::where('id_product', $validatedData['id_product'])
        ->where('id_customer', $validatedData['id_customer'])
        ->first();

        if ($product) {
            $product->cantidad += 1;
            $product->save();
    
            return response()->json([
                "success" => true,
                "message" => "Producto actualizado correctamente",
            ]);
        } else {
            $product = new Shopping();
            $product->id_customer = $validatedData['id_customer'];
            $product->id_product = $validatedData['id_product'];
            $product->save();
    
            return response()->json([
                "success" => true,
                "message" => "Producto creado correctamente",
            ]);
        }
    }

    public function getAllByCustomerId(Request $request, $id_customer)
    {
        $shopping = Shopping::where('id_customer', $id_customer)
            ->get(['id', 'cantidad', 'id_product']);
    
        foreach ($shopping as $item) {
            $product = Product::find($item->id_product);
            $item->product = $product;
            $product->photo = Storage::url($product->photo);
        }
    
        return response()->json([
            'success' => true,
            'message' => "Productos obtenidos correctamente",
            'data' => $shopping
        ]);
    }
    
    
    
    

    public function delete(Request $request, $id)
    {
        $product = Shopping::findOrFail($id);

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => "Producto borrado correctamente",
        ]);
    }

    public function deleteByIdProduct(Request $request, $id_product)
{
    $products = Shopping::where('id_product', $id_product)->get();

    foreach ($products as $product) {
        $product->delete();
    }

    return response()->json([
        'success' => true,
        'message' => "Productos borrados correctamente",
    ]);
}


    public function update(Request $request, $id)
    {
        $product = Shopping::findOrFail($id);
        if ($product) {
            $product->cantidad = $request->input('cantidad');
            $product->save();

            return response()->json([
                'success' => true,
                'message' => "Producto modificado correctamente",
            ]);
        }
    }
}
