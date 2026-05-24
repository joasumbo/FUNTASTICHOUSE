<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;

class GaleriaController extends Controller
{
    public function index()
    {
        $images = GalleryImage::where('active', true)
            ->orderBy('order')
            ->get()
            ->groupBy('category');

        return view('galeria.index', compact('images'));
    }
}
