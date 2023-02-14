<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'photo' => 'required|image|max:4048', // Ejemplo: tamaño máximo de 4MB
        ]);

        $photo = $request->file('photo');
        
        $blog = new Blog();
        $blog->title = $validatedData['title'];
        $blog->description = $validatedData['description'];
        $blog->photo = $photo->store('public/blog');
        $blog->save();
    
        $response = [
            'success' => true,
            'message' => "Post creado correctamente"
        ];
        return response()->json($response);
    }

    public function getAll(Request $request)
    {
        $blogs = Blog::all(); // <- Hay que importar el modelo
        $response = [
            'success' => true,
            'message' => "Blogs obtenidos correctamente",
            'data' => $blogs
        ];
        return response()->json($response);
    }
    
}
