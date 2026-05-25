<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    private const PAGE_MAP = [
        'home'               => 'Início',
        'porque-nos'         => 'Porquê Nós',
        'galeria'            => 'Galeria',
        'o-que-fazer'        => 'O Que Fazer',
        'reservas'           => 'Reservas',
        'reservas.sucesso'   => 'Reservas — Sucesso',
        'contactos'          => 'Contactos',
        'paginas.politica'   => 'Política de Privacidade',
        'paginas.termos'     => 'Termos e Condições',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$request->isMethod('GET')) {
            return $response;
        }

        if ($request->is('admin/*') || $request->is('sitemap.xml') || $request->is('lang/*') || $request->is('up')) {
            return $response;
        }

        if ($request->expectsJson() || $request->ajax()) {
            return $response;
        }

        $status = $response->getStatusCode();
        if ($status < 200 || $status >= 300) {
            return $response;
        }

        $ua = $request->userAgent() ?? '';
        if (preg_match('/bot|crawl|slurp|spider|mediapartners|lighthouse|headless|prerender|facebookexternalhit|whatsapp|telegrambot/i', $ua)) {
            return $response;
        }

        try {
            $routeName = $request->route()?->getName() ?? '';
            $pageName  = self::PAGE_MAP[$routeName] ?? null;

            if ($routeName === 'experiencia.show') {
                $slug     = $request->route('slug');
                $pageName = 'Experiência: ' . $slug;
            }

            $referrer = $request->header('referer');

            PageView::create([
                'path'       => '/' . ltrim($request->path(), '/'),
                'page_name'  => $pageName,
                'session_id' => $request->session()->getId(),
                'ip'         => $request->ip(),
                'referrer'   => $referrer ? mb_substr($referrer, 0, 500) : null,
                'source'     => $this->source($referrer),
                'device'     => $this->device($ua),
                'created_at' => now(),
            ]);
        } catch (\Throwable) {
            // Never break the request because of analytics
        }

        return $response;
    }

    private function source(?string $referrer): string
    {
        if (!$referrer) {
            return 'Direto';
        }

        $host = strtolower(parse_url($referrer, PHP_URL_HOST) ?? '');

        $map = [
            'google'    => 'Google',
            'bing'      => 'Bing',
            'yahoo'     => 'Yahoo',
            'facebook'  => 'Facebook',
            'fb.com'    => 'Facebook',
            'instagram' => 'Instagram',
            'twitter'   => 'Twitter/X',
            'x.com'     => 'Twitter/X',
            'tiktok'    => 'TikTok',
            'youtube'   => 'YouTube',
            'linkedin'  => 'LinkedIn',
        ];

        foreach ($map as $needle => $label) {
            if (str_contains($host, $needle)) {
                return $label;
            }
        }

        $appHost = strtolower(parse_url(config('app.url'), PHP_URL_HOST) ?? '');
        if ($appHost && str_contains($host, $appHost)) {
            return 'Interno';
        }

        return $host ?: 'Outro';
    }

    private function device(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'ipad') || str_contains($ua, 'tablet') ||
            (str_contains($ua, 'android') && !str_contains($ua, 'mobile'))) {
            return 'tablet';
        }
        if (str_contains($ua, 'mobile') || str_contains($ua, 'iphone') || str_contains($ua, 'android')) {
            return 'mobile';
        }
        return 'desktop';
    }
}
