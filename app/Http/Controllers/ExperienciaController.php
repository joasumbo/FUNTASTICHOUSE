<?php

namespace App\Http\Controllers;

use App\Models\Experience;

class ExperienciaController extends Controller
{
    public function show(string $slug)
    {
        $experience = Experience::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        return view('experiencia.show', compact('experience'));
    }
}
