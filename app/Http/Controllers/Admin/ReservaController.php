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
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
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

    public function show(Reservation $reservation)
    {
        $reservation->load('experience');
        return view('admin.reservas.show', compact('reservation'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => ['required', 'in:confirmed,cancelled,pending'],
        ]);

        $reservation->update(['status' => $request->status]);
        $reservation->load('experience');

        Mail::to($reservation->email)->send(new ReservationStatusChanged($reservation));

        $labels = ['confirmed' => 'confirmada', 'cancelled' => 'cancelada', 'pending' => 'marcada como pendente'];
        $label  = $labels[$request->status] ?? 'actualizada';

        return back()->with('success', "Reserva #{$reservation->id} {$label} com sucesso.");
    }
}
