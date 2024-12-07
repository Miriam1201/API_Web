<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validar la solicitud de actualización del perfil
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|max:2048',
        ]);

        // Actualizar la información del usuario
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Si el usuario sube una nueva imagen de perfil
        if ($request->hasFile('profile_image')) {
            try {
                // Guardar la imagen de perfil
                if ($user->profile_image && \Storage::exists(str_replace('/storage/', 'public/', $user->profile_image))) {
                    \Storage::delete(str_replace('/storage/', 'public/', $user->profile_image));
                }

                $avatarPath = $request->file('profile_image')->store('public/profile_images');
                $user->profile_image = str_replace('public/', '/storage/', $avatarPath);
            } catch (\Exception $e) {
                Log::error('Error al guardar la imagen de perfil: ' . $e->getMessage());
                return redirect()->back()->withErrors(['error' => 'No se pudo cargar la imagen de perfil.']);
            }
        }

        // Guardar los cambios
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Perfil actualizado con éxito');
    }



    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
