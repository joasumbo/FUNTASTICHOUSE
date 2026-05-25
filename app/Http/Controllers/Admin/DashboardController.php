<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\PageView;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now       = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $stats = [
            'total'      => Reservation::count(),
            'pending'    => Reservation::where('status', 'pending')->count(),
            'confirmed'  => Reservation::where('status', 'confirmed')->count(),
            'this_month' => Reservation::whereMonth('created_at', $now->month)
                               ->whereYear('created_at', $now->year)->count(),
            'last_month' => Reservation::whereMonth('created_at', $lastMonth->month)
                               ->whereYear('created_at', $lastMonth->year)->count(),
        ];

        $monthly = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now()->subMonths($i);
            $monthly[] = [
                'label'   => $d->locale('pt_PT')->isoFormat('MMM'),
                'count'   => Reservation::whereMonth('created_at', $d->month)
                                 ->whereYear('created_at', $d->year)->count(),
                'current' => $i === 0,
            ];
        }

        $recent      = Reservation::with('experience')->latest()->take(5)->get();
        $byExperience = Experience::withCount('reservations')
            ->withCount(['reservations as pending_count'   => fn ($q) => $q->where('status', 'pending')])
            ->withCount(['reservations as confirmed_count' => fn ($q) => $q->where('status', 'confirmed')])
            ->get();

        $confirmRate = $stats['total'] > 0
            ? round(($stats['confirmed'] / $stats['total']) * 100, 1)
            : 0;

        // ── Site analytics ──────────────────────────────────────────────────
        $analytics = [
            'today'        => PageView::whereDate('created_at', today())->count(),
            'week'         => PageView::where('created_at', '>=', now()->subDays(7))->count(),
            'month'        => PageView::where('created_at', '>=', now()->subDays(30))->count(),
            'unique_month' => PageView::where('created_at', '>=', now()->subDays(30))
                                 ->distinct('session_id')->count('session_id'),
        ];

        // Daily views for 30-day chart
        $rawDaily = PageView::select(
                DB::raw('DATE(created_at) as d'),
                DB::raw('COUNT(*) as v'),
                DB::raw('COUNT(DISTINCT session_id) as u')
            )
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->groupBy('d')
            ->get()
            ->keyBy('d');

        $dailyViews = [];
        for ($i = 29; $i >= 0; $i--) {
            $date         = now()->subDays($i)->format('Y-m-d');
            $row          = $rawDaily->get($date);
            $dailyViews[] = [
                'date'   => now()->subDays($i)->locale('pt_PT')->isoFormat('D MMM'),
                'views'  => (int) ($row->v ?? 0),
                'unique' => (int) ($row->u ?? 0),
            ];
        }

        // Top pages
        $topPages = PageView::where('created_at', '>=', now()->subDays(30))
            ->whereNotNull('page_name')
            ->select('page_name', DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT session_id) as uniq'))
            ->groupBy('page_name')
            ->orderByDesc('views')
            ->limit(8)
            ->get();

        // Traffic sources
        $sources = PageView::where('created_at', '>=', now()->subDays(30))
            ->select('source', DB::raw('COUNT(*) as views'))
            ->groupBy('source')
            ->orderByDesc('views')
            ->limit(7)
            ->get();

        // Device breakdown
        $devices = PageView::where('created_at', '>=', now()->subDays(30))
            ->select('device', DB::raw('COUNT(*) as views'))
            ->groupBy('device')
            ->orderByDesc('views')
            ->get();

        $trend = $stats['last_month'] > 0
            ? round((($stats['this_month'] - $stats['last_month']) / $stats['last_month']) * 100, 1)
            : ($stats['this_month'] > 0 ? 100 : 0);

        return view('admin.dashboard', compact(
            'stats', 'monthly', 'recent', 'byExperience', 'confirmRate', 'trend',
            'analytics', 'dailyViews', 'topPages', 'sources', 'devices'
        ));
    }
}
