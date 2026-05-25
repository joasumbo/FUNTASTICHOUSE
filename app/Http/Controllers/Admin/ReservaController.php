<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReservationStatusChanged;
use App\Models\Experience;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with('experience')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('experience_id')) {
            $query->where('experience_id', $request->experience_id);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
                if (is_numeric($search)) {
                    $q->orWhere('id', (int) $search);
                }
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('check_in', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('check_in', '<=', $request->date_to);
        }

        $reservations = $query->paginate(15)->withQueryString();
        $experiences  = Experience::orderBy('name_pt')->get();

        $counts = [
            'all'       => Reservation::count(),
            'pending'   => Reservation::where('status', 'pending')->count(),
            'confirmed' => Reservation::where('status', 'confirmed')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
        ];

        return view('admin.reservas.index', compact('reservations', 'experiences', 'counts'));
    }

    public function liveSearch(Request $request)
    {
        $query = Reservation::with('experience')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $s = trim($request->q);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
                if (is_numeric($s)) {
                    $q->orWhere('id', (int) $s);
                }
            });
        }

        if ($request->filled('experience_id')) {
            $query->where('experience_id', $request->experience_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('check_in', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('check_in', '<=', $request->date_to);
        }

        $threshold = now()->subMinutes(30);

        return $query->take(150)->get()->map(fn ($r) => [
            'id'         => $r->id,
            'name'       => $r->name,
            'email'      => $r->email,
            'phone'      => $r->phone ?? '',
            'experience' => $r->experience?->name_pt ?? '—',
            'check_in'   => $r->check_in->format('d/m/Y'),
            'check_out'  => $r->check_out->format('d/m/Y'),
            'guests'     => $r->guests,
            'status'     => $r->status,
            'created_at' => $r->created_at->format('d/m/Y'),
            'is_nova'    => is_null($r->viewed_at) && $r->created_at->gt($threshold),
            'url'        => route('admin.reservas.show', $r),
            'status_url' => route('admin.reservas.status', $r),
        ]);
    }

    public function novaCount()
    {
        $count = Reservation::where('created_at', '>=', now()->subMinutes(30))
            ->whereNull('viewed_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('experience');

        if (is_null($reservation->viewed_at)) {
            $reservation->update(['viewed_at' => now()]);
        }

        return view('admin.reservas.show', compact('reservation'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => ['required', 'in:confirmed,cancelled,pending'],
        ]);

        $reservation->update([
            'status'    => $request->status,
            'viewed_at' => $reservation->viewed_at ?? now(),
        ]);

        $reservation->load('experience');

        Mail::to($reservation->email)->send(new ReservationStatusChanged($reservation));

        $labels = ['confirmed' => 'confirmada', 'cancelled' => 'cancelada', 'pending' => 'marcada como pendente'];
        $label  = $labels[$request->status] ?? 'actualizada';

        return back()->with('success', "Reserva #{$reservation->id} {$label} com sucesso.");
    }
}
