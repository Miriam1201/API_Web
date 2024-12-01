<div class="p-6 bg-gray-200 text-gray-900 rounded-lg shadow-md space-y-4">
    <h2 class="text-2xl font-bold mb-4">Publicar Imágenes</h2>
    <form action="{{ route('community.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="username" placeholder="Introduce tu nombre de usuario" required maxlength="255"
            class="w-full p-3 bg-gray-100 rounded-md border border-gray-400 text-gray-700" />
        <input type="email" name="email" placeholder="Introduce tu correo electrónico" required maxlength="255"
            class="w-full p-3 bg-gray-100 rounded-md border border-gray-400 mt-4 text-gray-700" />
        <label for="profile_image" class="block mt-4">Avatar</label>
        <input type="file" name="profile_image" accept="image/*" class="w-full text-gray-700 mt-2" />
        <textarea name="content" placeholder="Introduce la descripción"
            class="w-full p-3 bg-gray-100 rounded-md border border-gray-400 mt-4 text-gray-700"></textarea>
        <label for="images" class="block mt-4">Imágenes</label>
        <input type="file" name="images[]" accept="image/*" multiple class="w-full text-gray-700 mt-2" />
        <input type="text" name="tags" placeholder="Introduce tags (opcional, separado por comas)"
            class="w-full p-3 bg-gray-100 rounded-md border border-gray-400 mt-4 text-gray-700" />
        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md mt-4 w-full">Publicar</button>
    </form>
</div>
