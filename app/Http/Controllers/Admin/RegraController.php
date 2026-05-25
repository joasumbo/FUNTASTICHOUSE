<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule as ValidationRule;

class RegraController extends Controller
{
    public function index(Request $request)
    {
        $query = Rule::with('experience')->orderBy('priority')->orderBy('created_at', 'desc');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $rules       = $query->get();
        $experiences = Experience::orderBy('name_pt')->get();

        $counts = [
            'all'          => Rule::count(),
            'availability' => Rule::where('category', 'availability')->count(),
            'pricing'      => Rule::where('category', 'pricing')->count(),
            'active'       => Rule::where('active', true)->count(),
        ];

        return view('admin.regras.index', compact('rules', 'experiences', 'counts'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Rule::create($data);
        return back()->with('success', "Regra \"{$data['name']}\" criada.");
    }

    public function update(Request $request, Rule $rule)
    {
        $data = $this->validated($request);
        $rule->update($data);
        return back()->with('success', "Regra \"{$rule->name}\" actualizada.");
    }

    public function destroy(Rule $rule)
    {
        $name = $rule->name;
        $rule->delete();
        return back()->with('success', "Regra \"{$name}\" eliminada.");
    }

    public function toggle(Rule $rule)
    {
        $rule->update(['active' => !$rule->active]);
        $state = $rule->active ? 'activada' : 'desactivada';
        return back()->with('success', "Regra \"{$rule->name}\" {$state}.");
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'             => 'required|string|max:120',
            'description'      => 'nullable|string|max:500',
            'experience_id'    => 'nullable|exists:experiences,id',
            'category'         => ['required', ValidationRule::in(['availability', 'pricing'])],
            'trigger_metric'   => ['required', ValidationRule::in([
                'confirmed_reservations',
                'pending_reservations',
                'total_reservations',
                'occupancy_pct',
            ])],
            'trigger_operator' => ['required', ValidationRule::in(['gte', 'lte', 'gt', 'lt', 'eq'])],
            'trigger_value'    => 'required|numeric|min:0|max:9999',
            'action_type'      => ['required', ValidationRule::in([
                'block_date', 'unblock_date', 'price_increase', 'price_decrease',
            ])],
            'action_value'     => 'nullable|numeric|min:0|max:9999',
            'action_unit'      => ['required', ValidationRule::in(['fixed', 'percent'])],
            'active'           => 'boolean',
            'priority'         => 'integer|min:0|max:999',
        ]);
    }
}
