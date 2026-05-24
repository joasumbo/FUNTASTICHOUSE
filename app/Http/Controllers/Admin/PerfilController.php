<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class PerfilController extends Controller
{
    public function index()
    {
        return view('admin.perfil.index');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'  => 'O nome é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.email'    => 'Email inválido.',
            'email.unique'   => 'Este email já está em uso.',
            'photo.image'    => 'O ficheiro deve ser uma imagem.',
            'photo.max'      => 'A imagem não pode exceder 2MB.',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('avatars', 'public');
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Perfil actualizado com sucesso.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Introduz a senha actual.',
            'password.required'         => 'Introduz a nova senha.',
            'password.confirmed'        => 'As senhas não coincidem.',
            'password.min'              => 'A senha deve ter pelo menos 8 caracteres.',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'A senha actual está incorrecta.'])->with('tab', 'password');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Senha alterada com sucesso.')->with('tab', 'password');
    }
}
