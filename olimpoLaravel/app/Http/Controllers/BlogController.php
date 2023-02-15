<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $blogs = Blog::all(['id', 'title', 'description', 'photo']);

        foreach ($blogs as $blog) {
            $blog->photo = Storage::url($blog->photo);
        }

        $response = [
            'success' => true,
            'message' => "Blogs obtenidos correctamente",
            'data' => $blogs
        ];
        return response()->json($response);
    }

    public function getById(Request $request, $id)
    {
        $post = Blog::findOrFail($id);


        $post->photo = Storage::url($post->photo);

        $response = [
            'success' => true,
            'message' => "Post con id: " . $id . " obtenido",
            'data' => [$post]
        ];
        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        $post = Blog::findOrFail($id);

        if ($post) {
            $post->title = $request->input('title');
            $post->description = $request->input('description');

            // Verifica si hay una imagen nueva
            if ($request->hasFile('photo')) {
                $image = $request->file('photo');

                // Valida la imagen
                $validated = $request->validate([
                    'photo' => 'required|image|max:4048', // máx 4 MB
                ]);

                // Elimina la imagen antigua si existe
                if ($post->photo) {
                    Storage::delete($post->photo);
                }

                // Guarda la imagen nueva y guarda su nombre en la base de datos
                $imagePath = $image->store('public/blog');
                $post->photo = $imagePath;
            }
            $post->save();
            $response = [
                'success' => true,
                'message' => "Post modificado correctamente",
            ];
            return response()->json($response);
        }


        
    }



    public function delete(Request $request, $id)
    {
        DB::table('blogs')->where('id', $id)->delete();
        $response = [
            'success' => true,
            'message' => "Post borrado correctamente",
        ];
        return response()->json($response);
    }

}