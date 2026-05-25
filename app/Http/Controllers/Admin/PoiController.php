<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poi;
use App\Models\PoiCategory;
use Illuminate\Http\Request;

class PoiController extends Controller
{
    public function index()
    {
        $categories = PoiCategory::withCount([
            'pois',
            'pois as active_pois_count' => fn ($q) => $q->where('active', true),
        ])->orderBy('name_pt')->get();

        $pois = Poi::with('category')
            ->orderBy('poi_category_id')
            ->orderBy('name_pt')
            ->get();

        $counts = [
            'all'      => $pois->count(),
            'active'   => $pois->where('active', true)->count(),
            'inactive' => $pois->where('active', false)->count(),
        ];

        return view('admin.pois.index', compact('categories', 'pois', 'counts'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['active'] = $request->boolean('active', true);
        Poi::create($data);

        return back()->with('success', 'Ponto de interesse adicionado.');
    }

    public function update(Request $request, Poi $poi)
    {
        $data = $this->validated($request);
        $data['active'] = $request->boolean('active', $poi->active);
        $poi->update($data);

        return back()->with('success', 'Ponto de interesse actualizado.');
    }

    public function destroy(Poi $poi)
    {
        $poi->delete();

        return back()->with('success', 'Ponto de interesse eliminado.');
    }

    public function toggle(Poi $poi)
    {
        $poi->update(['active' => !$poi->active]);
        $state = $poi->active ? 'activado' : 'desactivado';

        return back()->with('success', "POI {$state}.");
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name_pt' => 'required|string|max:80|unique:poi_categories,name_pt',
            'name_en' => 'required|string|max:80',
            'icon'    => 'nullable|string|max:60',
        ]);

        PoiCategory::create($data);

        return back()->with('success', 'Categoria criada.');
    }

    public function updateCategory(Request $request, PoiCategory $category)
    {
        $data = $request->validate([
            'name_pt' => 'required|string|max:80|unique:poi_categories,name_pt,' . $category->id,
            'name_en' => 'required|string|max:80',
            'icon'    => 'nullable|string|max:60',
        ]);

        $category->update($data);

        return back()->with('success', 'Categoria actualizada.');
    }

    public function destroyCategory(PoiCategory $category)
    {
        if ($category->pois()->exists()) {
            return back()->with('error', 'Não é possível eliminar uma categoria com POIs associados.');
        }

        $category->delete();

        return back()->with('success', 'Categoria eliminada.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'poi_category_id' => 'required|exists:poi_categories,id',
            'name_pt'         => 'required|string|max:160',
            'name_en'         => 'required|string|max:160',
            'description_pt'  => 'nullable|string|max:1000',
            'description_en'  => 'nullable|string|max:1000',
            'lat'             => 'required|numeric|between:-90,90',
            'lng'             => 'required|numeric|between:-180,180',
            'distance_km'     => 'nullable|numeric|min:0|max:999',
        ]);
    }
}
