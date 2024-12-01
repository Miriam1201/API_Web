<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunidad</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<!-- Encabezado con el logo y el título -->
<header class="bg-gray-800 p-6">
    <div class="container mx-auto">
        <div class="flex items-center">
            <a href="http://localhost:4321" class="flex items-center">
                <img src="logo.png" alt="Genshin Impact Logo" class="h-20 mr-4" />
                <span class="text-white text-2xl font-semibold tracking-wide">
                    Genshin Impact
                </span>
            </a>
        </div>
    </div>
</header>



<body class="bg-gray-800 text-white">
    <div class="container mx-auto mt-8 grid grid-cols-3 gap-8">
        <!-- Listado de Publicaciones -->
        <div class="col-span-2">
            <h2 class="text-3xl font-bold mb-6">Recomendado</h2>

            <div id="posts-list">
                @if ($posts->isEmpty())
                    <p>No hay publicaciones disponibles.</p>
                @else
                    @foreach ($posts as $post)
                        <div class="p-6 mb-6 bg-gray-100 text-gray-900 rounded-lg shadow-md relative">
                            <div class="flex items-center mb-4">
                                <img src="{{ $post->profile_image ?? '/images/avatar/default.png' }}"
                                    alt="Avatar de usuario" class="h-12 w-12 rounded-full mr-4">
                                <div>
                                    <h4 class="text-xl font-bold">{{ $post->username }}</h4>
                                    <p class="text-sm text-gray-600">{{ $post->created_at->format('Y-m-d') }} -
                                        {{ $post->game }}</p>
                                    <p>{{ $post->email }}</p>
                                </div>
                            </div>
                            <p class="mb-4">{{ $post->content }}</p>

                            <!-- Mostrar imágenes -->
                            @if (!empty($post->images))
                                @php
                                    $images = is_string($post->images)
                                        ? json_decode($post->images, true)
                                        : $post->images;
                                @endphp
                                @if ($images)
                                    <div class="flex flex-wrap -mx-2">
                                        @foreach ($images as $image)
                                            <div class="w-1/3 px-2 mb-4">
                                                <img src="{{ $image }}" alt="Imagen del post"
                                                    class="rounded-lg shadow-md">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endif

                            <!-- Mostrar video -->
                            @if ($post->video_url)
                                <div class="mt-4">
                                    <iframe width="560" height="315" class="rounded-lg"
                                        src="{{ $post->video_url }}" title="YouTube video player" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            @endif

                            <!-- Mostrar sticker aleatorio si existe -->
                            @if ($post->sticker)
                                <img src="{{ $post->sticker }}" alt="Sticker del post"
                                    class="absolute bottom-2 right-2 h-32 w-32">
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Formulario para Crear Publicación -->
        <div class="col-span-1 p-6 bg-gray-200 text-gray-900 rounded-lg shadow-md">
            <div class="tabs mb-6">
                <!-- Botones para cambiar entre pestañas -->
                <button data-tab="text"
                    class="tab-button active w-full text-left py-3 px-4 mb-2 rounded-lg bg-blue-600 text-white font-semibold">
                    Texto
                </button>
                <button data-tab="images"
                    class="tab-button w-full text-left py-3 px-4 mb-2 rounded-lg bg-gray-400 text-gray-900 font-semibold">
                    Imágenes
                </button>
                <button data-tab="video"
                    class="tab-button w-full text-left py-3 px-4 mb-2 rounded-lg bg-gray-400 text-gray-900 font-semibold">
                    Videos
                </button>
            </div>

            <!-- Contenido del formulario según la pestaña -->
            <div id="tab-content">
                @include('create-post-text')
            </div>
        </div>
    </div>

    <!-- Scripts para manejar las pestañas -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tabs = document.querySelectorAll(".tab-button");
            const tabContent = document.getElementById("tab-content");

            tabs.forEach(tab => {
                tab.addEventListener("click", function() {
                    tabs.forEach(t => {
                        t.classList.remove("active");
                        t.classList.remove("bg-blue-600");
                        t.classList.remove("text-white");
                        t.classList.add("bg-gray-400");
                        t.classList.add("text-gray-900");
                    });
                    tab.classList.add("active");
                    tab.classList.add("bg-blue-600");
                    tab.classList.add("text-white");
                    tab.classList.remove("bg-gray-400");
                    tab.classList.remove("text-gray-900");

                    let tabName = tab.getAttribute("data-tab");
                    switch (tabName) {
                        case 'text':
                            tabContent.innerHTML = `@include('create-post-text')`;
                            break;
                        case 'images':
                            tabContent.innerHTML = `@include('create-post-images')`;
                            break;
                        case 'video':
                            tabContent.innerHTML = `@include('create-post-video')`;
                            break;
                    }
                });
            });
        });
    </script>
</body>

</html>
