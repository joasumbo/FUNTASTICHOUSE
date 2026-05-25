<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PaginaController extends Controller
{
    public function index()
    {
        $pages = Page::all();

        return view('admin.paginas.index', compact('pages'));
    }

    public function edit(Page $page)
    {
        return view('admin.paginas.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title_pt'   => 'required|string|max:255',
            'title_en'   => 'required|string|max:255',
            'content_pt' => 'nullable|string',
            'content_en' => 'nullable|string',
            'active'     => 'nullable|boolean',
        ]);

        $validated['active'] = $request->boolean('active', true);

        $page->update($validated);

        return redirect()->route('admin.paginas')->with('success', 'Página atualizada com sucesso.');
    }
}
