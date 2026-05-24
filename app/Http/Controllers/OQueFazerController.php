<?php

namespace App\Http\Controllers;

use App\Models\PoiCategory;

class OQueFazerController extends Controller
{
    public function index()
    {
        $categories = PoiCategory::with([
            'pois' => fn ($q) => $q->where('active', true)->orderBy('distance_km'),
        ])->get();

        return view('o-que-fazer.index', compact('categories'));
    }
}
