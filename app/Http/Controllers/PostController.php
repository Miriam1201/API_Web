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
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string',
            'images.*' => 'nullable|image|max:2048',
            'profile_image' => 'nullable|image|max:2048',
            'video_url' => 'nullable|url',
        ]);

        // Inicializar un array vacío para almacenar los datos
        $data = $request->only(['username', 'email', 'content']);

        // Procesar el enlace del video para que sea del tipo 'embed'
        if ($request->filled('video_url')) {
            $videoUrl = $request->input('video_url');

            // Extraer el ID del video y construir el nuevo enlace embed
            if (preg_match('/watch\?v=([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
                $videoId = $matches[1];
                $data['video_url'] = "https://www.youtube.com/embed?v={$videoId}";
            } else {
                $data['video_url'] = $videoUrl; // Si el formato no coincide, lo dejamos como está
            }
        }

        // Guardar avatar si se proporciona
        if ($request->hasFile('profile_image')) {
            try {
                $avatarPath = $request->file('profile_image')->store('public/post/avatar');
                $data['profile_image'] = str_replace('public/', '/storage/', $avatarPath);
            } catch (\Exception $e) {
                Log::error('Error al guardar la imagen de perfil: ' . $e->getMessage());
                return redirect()->back()->withErrors(['error' => 'No se pudo cargar la imagen de perfil.']);
            }
        } else {
            $data['profile_image'] = '/images/avatar/default.png';
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
            return redirect()->route('community.index')->with('success', 'Publicación creada con éxito');
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
