<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class ConfiguracaoController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');

        return view('admin.configuracoes.index', compact('settings'));
    }

    public function updateGeral(Request $request)
    {
        $request->validate([
            'site_name'     => 'required|string|max:100',
            'logo'          => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'favicon'       => 'nullable|file|max:512',
            'admin_favicon' => 'nullable|file|max:512',
        ]);

        Setting::set('site_name', $request->site_name);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $ext  = $file->getClientOriginalExtension();
            $file->move(public_path('images'), 'logo.' . $ext);
            Setting::set('logo', 'images/logo.' . $ext);
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $ext  = $file->getClientOriginalExtension();
            $file->move(public_path(), 'favicon.' . $ext);
            Setting::set('favicon', 'favicon.' . $ext);
        }

        if ($request->hasFile('admin_favicon')) {
            $file = $request->file('admin_favicon');
            $ext  = $file->getClientOriginalExtension();
            $file->move(public_path('images'), 'admin-favicon.' . $ext);
            Setting::set('admin_favicon', 'images/admin-favicon.' . $ext);
        }

        return back()->with('success', 'Configurações gerais guardadas.')->with('active_tab', 'geral');
    }

    public function updateContactos(Request $request)
    {
        $request->validate([
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:100',
            'whatsapp'       => 'nullable|string|max:30',
            'address'        => 'nullable|string|max:200',
            'address_full'   => 'nullable|string|max:500',
            'maps_embed_url' => 'nullable|string|max:2000',
        ]);

        foreach (['phone', 'email', 'whatsapp', 'address', 'address_full', 'maps_embed_url'] as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        return back()->with('success', 'Contactos guardados.')->with('active_tab', 'contactos');
    }

    public function updateSocial(Request $request)
    {
        $request->validate([
            'instagram_url' => 'nullable|url|max:200',
            'facebook_url'  => 'nullable|url|max:200',
        ]);

        Setting::set('instagram_url', $request->input('instagram_url', ''));
        Setting::set('facebook_url', $request->input('facebook_url', ''));

        return back()->with('success', 'Redes sociais guardadas.')->with('active_tab', 'social');
    }

    public function updateSeo(Request $request)
    {
        $request->validate([
            'meta_title_pt' => 'nullable|string|max:160',
            'meta_title_en' => 'nullable|string|max:160',
            'meta_desc_pt'  => 'nullable|string|max:320',
            'meta_desc_en'  => 'nullable|string|max:320',
            'og_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        foreach (['meta_title_pt', 'meta_title_en', 'meta_desc_pt', 'meta_desc_en'] as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        if ($request->hasFile('og_image')) {
            $file = $request->file('og_image');
            $ext  = $file->getClientOriginalExtension();
            $file->move(public_path('images'), 'og-image.' . $ext);
            Setting::set('og_image', 'images/og-image.' . $ext);
        }

        return back()->with('success', 'SEO guardado.')->with('active_tab', 'seo');
    }
}
