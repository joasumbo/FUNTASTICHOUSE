<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GaleriaController extends Controller
{
    public function index(Request $request)
    {
        $query = GalleryImage::with('experience')->orderBy('order')->orderBy('id');

        if ($request->filled('experience_id')) {
            $query->where('experience_id', $request->experience_id);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $images      = $query->get();
        $experiences = Experience::orderBy('name_pt')->get();
        $categories  = GalleryImage::distinct()->orderBy('category')->pluck('category');

        $counts = [
            'all'      => GalleryImage::count(),
            'active'   => GalleryImage::where('active', true)->count(),
            'inactive' => GalleryImage::where('active', false)->count(),
        ];

        return view('admin.galeria.index', compact('images', 'experiences', 'categories', 'counts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'images'        => 'required|array|min:1',
            'images.*'      => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:10240',
            'experience_id' => 'nullable|exists:experiences,id',
            'category'      => 'required|string|max:80',
        ]);

        $dir = public_path('images/gallery');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $maxOrder = GalleryImage::max('order') ?? 0;
        $count    = 0;

        foreach ($request->file('images') as $file) {
            $ext      = strtolower($file->getClientOriginalExtension());
            $base     = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . $base . '_' . uniqid() . '.' . $ext;
            $file->move($dir, $filename);

            GalleryImage::create([
                'experience_id' => $request->experience_id ?: null,
                'category'      => $request->category,
                'filename'      => 'images/gallery/' . $filename,
                'order'         => ++$maxOrder,
                'active'        => true,
            ]);
            $count++;
        }

        return back()->with('success', $count === 1 ? '1 imagem adicionada.' : "{$count} imagens adicionadas.");
    }

    public function update(Request $request, GalleryImage $image)
    {
        $data = $request->validate([
            'category'      => 'required|string|max:80',
            'experience_id' => 'nullable|exists:experiences,id',
            'alt_pt'        => 'nullable|string|max:200',
            'alt_en'        => 'nullable|string|max:200',
            'order'         => 'integer|min:0|max:9999',
        ]);

        $data['experience_id'] = $data['experience_id'] ?: null;
        $image->update($data);

        return back()->with('success', 'Imagem actualizada.');
    }

    public function destroy(GalleryImage $image)
    {
        $path = public_path($image->filename);
        if (file_exists($path) && str_contains($image->filename, 'images/gallery/')) {
            unlink($path);
        }
        $image->delete();

        return back()->with('success', 'Imagem eliminada.');
    }

    public function toggle(GalleryImage $image)
    {
        $image->update(['active' => !$image->active]);
        $state = $image->active ? 'activada' : 'desactivada';

        return back()->with('success', "Imagem {$state}.");
    }
}
