<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    // Mostrar la vista de comunidad con publicaciones recientes
    public function index()
    {
        $posts = Post::latest()->get();

        // Añadir un sticker aleatorio a cada publicación
        foreach ($posts as $post) {
            $stickerFiles = File::files(public_path('stickers'));
            if (!empty($stickerFiles)) {
                $randomSticker = $stickerFiles[array_rand($stickerFiles)];
                $post->sticker = '/stickers/' . $randomSticker->getFilename();
            } else {
                $post->sticker = null;
            }
        }

        return view('community.index', compact('posts'));
    }

    public function store(Request $request)
    {
        // Validación del formulario
        $request->validate([
            'content' => 'required|string',
            'images.*' => 'nullable|image|max:2048',
            'video_url' => 'nullable|url',
            'tags' => 'nullable|string'
        ]);

        // Inicializar un array para almacenar los datos del post
        $user = auth()->user();
        $data = [
            'username' => $user->name,
            'email' => $user->email,
            'profile_image' => $user->profile_image ?? '/images/avatar/default.png', // Imagen de perfil predeterminada si no tiene avatar
            'content' => $request->input('content'),
            'tags' => $request->input('tags', ''),
        ];

        // Procesar el enlace del video para que sea del tipo 'embed'
        if ($request->filled('video_url')) {
            $videoUrl = $request->input('video_url');

            // Convertir el enlace al formato de embed
            if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
                $videoId = $matches[1];
                $data['video_url'] = "https://www.youtube.com/embed?{$videoId}";
            } elseif (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
                $videoId = $matches[1];
                $data['video_url'] = "https://www.youtube.com/embed?{$videoId}";
            } else {
                $data['video_url'] = $videoUrl;
            }
        }



        // Guardar las imágenes del post si se proporcionan
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $path = $image->store('public/post/images');
                    $formattedPath = str_replace('public/', '/storage/', $path);
                    $imagePaths[] = $formattedPath;
                } catch (\Exception $e) {
                    Log::error('Error al guardar una de las imágenes del post: ' . $e->getMessage());
                    return redirect()->back()->withErrors(['error' => 'No se pudo cargar una o más imágenes.']);
                }
            }
        }

        // Asegurarnos de que `$imagePaths` esté formateado correctamente como array
        $data['images'] = !empty($imagePaths) ? json_encode($imagePaths) : json_encode([]);

        // Crear la publicación en la base de datos
        try {
            $post = Post::create($data);
            return redirect()->route('comunidad')->with('success', 'Publicación creada con éxito');
        } catch (\Exception $e) {
            Log::error('Error al crear la publicación: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Hubo un problema al crear la publicación, por favor intente de nuevo.']);
        }
    }





    // Devolver la vista de crear publicación de texto
    public function createText()
    {
        return view('create-post-text');
    }

    // Devolver la vista de crear publicación de imágenes
    public function createImages()
    {
        return view('create-post-images');
    }

    // Devolver la vista de crear publicación de video
    public function createVideo()
    {
        return view('create-post-video');
    }
}
