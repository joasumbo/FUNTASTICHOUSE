<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UtilizadorController extends Controller
{
    public const SECTIONS = [
        'dashboard'     => 'Dashboard',
        'reservas'      => 'Reservas',
        'calendario'    => 'Calendário',
        'precario'      => 'Preçário',
        'regras'        => 'Regras',
        'galeria'       => 'Galeria',
        'pois'          => 'Pontos de Interesse',
        'testemunhos'   => 'Testemunhos',
        'paginas'       => 'Páginas',
        'configuracoes' => 'Configurações',
    ];

    public function index()
    {
        $users = User::where('is_admin', true)->latest()->get();
        return view('admin.utilizadores.index', compact('users'));
    }

    public function create()
    {
        return view('admin.utilizadores.create', ['sections' => self::SECTIONS]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:60|unique:users|alpha_dash',
            'email'    => 'required|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => 'required|in:superadmin,staff',
        ], [
            'name.required'     => 'O nome é obrigatório.',
            'username.required' => 'O nome de utilizador é obrigatório.',
            'username.unique'   => 'Este nome de utilizador já existe.',
            'username.alpha_dash' => 'O nome de utilizador só pode ter letras, números, traços e underscores.',
            'email.required'    => 'O email é obrigatório.',
            'email.unique'      => 'Este email já está em uso.',
            'password.required' => 'A senha é obrigatória.',
            'password.confirmed'=> 'As senhas não coincidem.',
            'password.min'      => 'A senha deve ter pelo menos 8 caracteres.',
        ]);

        $permissions = null;
        if ($request->role === 'staff') {
            $permissions = [];
            foreach (array_keys(self::SECTIONS) as $sec) {
                $permissions[$sec] = $request->boolean('perm_' . $sec);
            }
        }

        User::create([
            'name'              => $request->name,
            'username'          => $request->username,
            'email'             => $request->email,
            'password'          => $request->password,
            'role'              => $request->role,
            'is_admin'          => true,
            'permissions'       => $permissions,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.utilizadores')
            ->with('success', 'Utilizador criado com sucesso.');
    }

    public function edit(User $utilizador)
    {
        return view('admin.utilizadores.edit', [
            'utilizador' => $utilizador,
            'sections'   => self::SECTIONS,
        ]);
    }

    public function update(Request $request, User $utilizador)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:60|alpha_dash|unique:users,username,' . $utilizador->id,
            'email'    => 'required|email|max:255|unique:users,email,' . $utilizador->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role'     => 'required|in:superadmin,staff',
        ], [
            'name.required'      => 'O nome é obrigatório.',
            'username.required'  => 'O nome de utilizador é obrigatório.',
            'username.unique'    => 'Este nome de utilizador já existe.',
            'username.alpha_dash'=> 'O nome de utilizador só pode ter letras, números, traços e underscores.',
            'email.required'     => 'O email é obrigatório.',
            'email.unique'       => 'Este email já está em uso.',
            'password.confirmed' => 'As senhas não coincidem.',
            'password.min'       => 'A senha deve ter pelo menos 8 caracteres.',
        ]);

        if ($utilizador->id === auth()->id() && $request->role !== 'superadmin') {
            return back()->withErrors(['role' => 'Não pode alterar o seu próprio papel.']);
        }

        $permissions = null;
        if ($request->role === 'staff') {
            $permissions = [];
            foreach (array_keys(self::SECTIONS) as $sec) {
                $permissions[$sec] = $request->boolean('perm_' . $sec);
            }
        }

        $data = [
            'name'        => $request->name,
            'username'    => $request->username,
            'email'       => $request->email,
            'role'        => $request->role,
            'permissions' => $permissions,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $utilizador->update($data);

        return redirect()->route('admin.utilizadores')
            ->with('success', 'Utilizador actualizado com sucesso.');
    }

    public function destroy(User $utilizador)
    {
        if ($utilizador->id === auth()->id()) {
            return back()->with('error', 'Não pode eliminar a sua própria conta.');
        }

        if ($utilizador->role === 'superadmin' && User::where('role', 'superadmin')->count() <= 1) {
            return back()->with('error', 'Não é possível eliminar o único superadmin.');
        }

        if ($utilizador->photo) {
            Storage::disk('public')->delete($utilizador->photo);
        }

        $utilizador->delete();

        return back()->with('success', 'Utilizador eliminado.');
    }
}
