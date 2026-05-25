<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\PricingRule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PrecarioController extends Controller
{
    public function index()
    {
        $experiences = Experience::with(['pricingRules' => fn ($q) => $q->orderBy('start_date')])->get();
        return view('admin.precario.index', compact('experiences'));
    }

    public function updatePrices(Request $request, Experience $experience)
    {
        $data = $request->validate([
            'base_price'    => 'required|numeric|min:0|max:9999.99',
            'weekend_price' => 'required|numeric|min:0|max:9999.99',
        ]);

        $experience->update($data);

        return back()->with('success', "Preços de \"{$experience->name_pt}\" actualizados.");
    }

    public function storeRule(Request $request)
    {
        $data = $request->validate([
            'experience_id'   => 'required|exists:experiences,id',
            'season'          => ['required', Rule::in(['high', 'medium', 'low'])],
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after:start_date',
            'price_per_night' => 'required|numeric|min:0|max:9999.99',
        ]);

        PricingRule::create($data);

        return back()->with('success', 'Época de preço adicionada.');
    }

    public function updateRule(Request $request, PricingRule $rule)
    {
        $data = $request->validate([
            'season'          => ['required', Rule::in(['high', 'medium', 'low'])],
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after:start_date',
            'price_per_night' => 'required|numeric|min:0|max:9999.99',
        ]);

        $rule->update($data);

        return back()->with('success', 'Época de preço actualizada.');
    }

    public function destroyRule(PricingRule $rule)
    {
        $rule->delete();
        return back()->with('success', 'Época de preço eliminada.');
    }
}
