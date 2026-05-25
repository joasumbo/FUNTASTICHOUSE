<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestemunhoController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('order')->orderBy('id')->get();

        $counts = [
            'all'      => $testimonials->count(),
            'active'   => $testimonials->where('active', true)->count(),
            'inactive' => $testimonials->where('active', false)->count(),
            'avg'      => $testimonials->count() ? round($testimonials->avg('rating'), 1) : 0,
        ];

        return view('admin.testemunhos.index', compact('testimonials', 'counts'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['active'] = $request->boolean('active', true);
        $data['order']  = (Testimonial::max('order') ?? 0) + 1;

        Testimonial::create($data);

        return back()->with('success', 'Testemunho adicionado.');
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $this->validated($request);
        $data['active'] = $request->boolean('active', $testimonial->active);

        $testimonial->update($data);

        return back()->with('success', 'Testemunho actualizado.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return back()->with('success', 'Testemunho eliminado.');
    }

    public function toggle(Testimonial $testimonial)
    {
        $testimonial->update(['active' => !$testimonial->active]);
        $state = $testimonial->active ? 'activado' : 'desactivado';

        return back()->with('success', "Testemunho {$state}.");
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer|exists:testimonials,id']);

        foreach ($request->order as $position => $id) {
            Testimonial::where('id', $id)->update(['order' => $position + 1]);
        }

        return response()->json(['ok' => true]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'author_name'     => 'required|string|max:100',
            'author_location' => 'nullable|string|max:100',
            'content_pt'      => 'required|string|max:1000',
            'content_en'      => 'required|string|max:1000',
            'rating'          => 'required|integer|min:1|max:5',
        ]);
    }
}
