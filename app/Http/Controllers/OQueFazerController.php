<?php

namespace App\Http\Controllers;

use App\Models\PoiCategory;

class OQueFazerController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();

        $categories = PoiCategory::with([
            'pois' => fn ($q) => $q->where('active', true)->orderBy('distance_km'),
        ])->get();

        $poisJson = $categories->flatMap(function ($cat) use ($locale) {
            return $cat->pois->map(fn ($p) => [
                'id'          => $p->id,
                'name'        => $locale === 'pt' ? $p->name_pt : $p->name_en,
                'description' => $locale === 'pt' ? $p->description_pt : $p->description_en,
                'lat'         => (float) $p->lat,
                'lng'         => (float) $p->lng,
                'distance_km' => (float) $p->distance_km,
                'category_id' => $cat->id,
                'category'    => $locale === 'pt' ? $cat->name_pt : $cat->name_en,
                'icon'        => $cat->icon,
            ]);
        })->values();

        return view('o-que-fazer.index', compact('categories', 'poisJson'));
    }
}
