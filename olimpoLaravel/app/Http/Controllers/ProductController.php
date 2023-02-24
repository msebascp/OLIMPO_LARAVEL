<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'photo' => 'required|image|max:4048', // Ejemplo: tamaño máximo de 4MB
        ]);
        $photo = $request->file('photo');

        $product = new Product();
        $product->name = $validatedData['name'];
        $product->description = $validatedData['description'];
        $product->photo = $photo->store('public/store');
        $product->save();

        return response()->json([
            "success" => true,
            "message" => "Producto creado correctamente",
        ]);
    }

    public function getAll(Request $request)
    {
        $products = Product::all(['id', 'name', 'description', 'photo']);
        foreach ($products as $product) {
            $product->photo = Storage::url($product->photo);
        }
        return response()->json([
            'success' => true,
            'message' => "Blogs obtenidos correctamente",
            'data' => $products
        ]);
    }

    public function getById(Request $request, $id)
    {
        $product = Product::findOrFail($id);


        $product->photo = Storage::url($product->photo);

        return response()->json([
            'success' => true,
            'message' => "Post con id: " . $id . " obtenido",
            'data' => [$product]
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if ($product) {
            $product->name = $request->input('name');
            $product->description = $request->input('description');

            // Verifica si hay una imagen nueva
            if ($request->hasFile('photo')) {
                $image = $request->file('photo');

                // Valida la imagen
                $validated = $request->validate([
                    'photo' => 'required|image|max:4048', // máx 4 MB
                ]);

                // Elimina la imagen antigua si existe
                if ($product->photo) {
                    Storage::delete($product->photo);
                }

                // Guarda la imagen nueva y guarda su nombre en la base de datos
                $imagePath = $image->store('public/store');
                $product->photo = $imagePath;
            }
            $product->save();

            return response()->json([
                'success' => true,
                'message' => "Producto modificado correctamente",
            ]);
        }
    }

    public function delete(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->photo) {
            Storage::delete($product->photo);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => "Post borrado correctamente",
        ]);
    }
}
