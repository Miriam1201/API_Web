<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;

class PostController extends Controller
{
    // Listar todas las publicaciones
    public function index()
    {
        return Post::all();
    }

    // Obtener una publicación específica
    public function show($id)
    {
        return Post::findOrFail($id);
    }

    // Crear una nueva publicación
    public function store(Request $request)
    {
        try {
            // Validar los datos de la solicitud
            $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'content' => 'required|string',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            Log::info('Validación completada');

            $data = $request->all(); // Obtener todos los datos del request

            // Guardar avatar si se proporciona
            if ($request->hasFile('profile_image')) {
                try {
                    $avatarPath = $request->file('profile_image')->store('public/post/avatar');
                    $data['profile_image'] = str_replace('public/', '/storage/', $avatarPath);
                    Log::info('Avatar almacenado en: ' . $data['profile_image']);
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Error al almacenar la imagen de perfil: ' . $e->getMessage()], 500);
                }
            } else {
                $data['profile_image'] = '/images/avatar/default.png';
            }

            // Guardar las imágenes del post si se proporcionan
            if ($request->hasFile('images')) {
                try {
                    $imagePaths = [];
                    foreach ($request->file('images') as $image) {
                        $path = $image->store('public/post/images');
                        $imagePaths[] = str_replace('public/', '/storage/', $path);
                    }
                    $data['images'] = json_encode($imagePaths);
                    Log::info('Imágenes almacenadas en: ' . json_encode($imagePaths));
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Error al almacenar las imágenes: ' . $e->getMessage()], 500);
                }
            } else {
                $data['images'] = json_encode([]);
            }

            Log::info('Datos antes de crear el post: ' . json_encode($data));

            // Crear el post en la base de datos
            try {
                $post = Post::create($data);
                Log::info('Post creado exitosamente con ID: ' . $post->id);
            } catch (\Exception $e) {
                Log::error('Error al crear el post: ' . $e->getMessage());
                return response()->json(['error' => 'Error al crear el post: ' . $e->getMessage()], 500);
            }

            return response()->json($post, 201);
        } catch (\Exception $e) {
            Log::error('Error inesperado: ' . $e->getMessage());
            return response()->json(['error' => 'Error inesperado: ' . $e->getMessage()], 500);
        }
    }



    // Actualizar una publicación existente
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $validatedData = $request->validate([
            'username' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'content' => 'sometimes|required|string',
            'tags' => 'sometimes|array',
            'tags.*' => 'string',
            'images' => 'sometimes|array',
            'images.*' => 'string',
        ]);

        $post->update($validatedData);
        return $post;
    }

    // Eliminar una publicación específica
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(['message' => 'Publicación eliminada correctamente']);
    }

    // Búsqueda personalizada de publicaciones (opcional)
    public function search(Request $request)
    {
        $query = Post::query();

        if ($request->has('username')) {
            $query->where('username', 'like', '%' . $request->input('username') . '%');
        }

        if ($request->has('content')) {
            $query->where('content', 'like', '%' . $request->input('content') . '%');
        }

        return $query->get();
    }
}
