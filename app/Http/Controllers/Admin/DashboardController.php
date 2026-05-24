<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Reservation;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now       = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $stats = [
            'total'          => Reservation::count(),
            'pending'        => Reservation::where('status', 'pending')->count(),
            'confirmed'      => Reservation::where('status', 'confirmed')->count(),
            'this_month'     => Reservation::whereMonth('created_at', $now->month)
                                    ->whereYear('created_at', $now->year)->count(),
            'last_month'     => Reservation::whereMonth('created_at', $lastMonth->month)
                                    ->whereYear('created_at', $lastMonth->year)->count(),
        ];

        // Monthly reservations for bar chart (last 7 months)
        $monthly = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now()->subMonths($i);
            $monthly[] = [
                'label' => $d->locale('pt_PT')->isoFormat('MMM'),
                'count' => Reservation::whereMonth('created_at', $d->month)
                               ->whereYear('created_at', $d->year)->count(),
                'current' => $i === 0,
            ];
        }

        // Recent reservations
        $recent = Reservation::with('experience')->latest()->take(5)->get();

        // By experience
        $byExperience = Experience::withCount('reservations')
            ->withCount(['reservations as pending_count' => fn($q) => $q->where('status', 'pending')])
            ->withCount(['reservations as confirmed_count' => fn($q) => $q->where('status', 'confirmed')])
            ->get();

        // Confirmation rate
        $confirmRate = $stats['total'] > 0
            ? round(($stats['confirmed'] / $stats['total']) * 100, 1)
            : 0;

        return view('admin.dashboard', compact('stats', 'monthly', 'recent', 'byExperience', 'confirmRate'));
    }
}
