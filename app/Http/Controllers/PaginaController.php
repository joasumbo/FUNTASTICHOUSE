<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PaginaController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->where('active', true)->firstOrFail();

        return view('paginas.show', compact('page'));
    }
}
