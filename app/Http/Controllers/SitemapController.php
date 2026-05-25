<?php

namespace App\Http\Controllers;

use App\Models\Experience;

class SitemapController extends Controller
{
    public function index()
    {
        $lastmod     = now()->toDateString();
        $experiences = Experience::all(['slug']);

        $urls = [
            ['loc' => url('/'),            'changefreq' => 'weekly',  'priority' => '1.0'],
            ['loc' => url('/porque-nos'),  'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => url('/galeria'),     'changefreq' => 'weekly',  'priority' => '0.8'],
            ['loc' => url('/o-que-fazer'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => url('/reservas'),    'changefreq' => 'daily',   'priority' => '0.9'],
            ['loc' => url('/contactos'),   'changefreq' => 'yearly',  'priority' => '0.6'],
        ];

        foreach ($experiences as $exp) {
            $urls[] = [
                'loc'        => url('/experiencia/' . $exp->slug),
                'changefreq' => 'weekly',
                'priority'   => '0.9',
            ];
        }

        return response()
            ->view('sitemap', compact('urls', 'lastmod'))
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
