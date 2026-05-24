<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $experiences  = Experience::where('active', true)->get();
        $testimonials = Testimonial::where('active', true)->orderBy('order')->take(3)->get();

        return view('home.index', compact('experiences', 'testimonials'));
    }
}
