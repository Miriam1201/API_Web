<div class="p-6 bg-gray-200 text-gray-900 rounded-lg shadow-md space-y-4">
    <h2 class="text-2xl font-bold mb-4">Publicar Video</h2>
    <form action="{{ route('community.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <textarea name="content" placeholder="Introduce la descripción"
            class="w-full p-3 bg-gray-100 rounded-md border border-gray-400 mt-4 text-gray-700"></textarea>
        <label for="video_url" class="block mt-4">Video</label>
        <input type="text" name="video_url" placeholder="Introduce el enlace del video"
            class="w-full p-3 bg-gray-100 rounded-md border border-gray-400 mt-2 text-gray-700" />
        <input type="text" name="tags" placeholder="Introduce tags (opcional, separado por comas)"
            class="w-full p-3 bg-gray-100 rounded-md border border-gray-400 mt-4 text-gray-700" />
        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md mt-4 w-full">Publicar</button>
    </form>
</div>
