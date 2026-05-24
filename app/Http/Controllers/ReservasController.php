<?php

namespace App\Http\Controllers;

use App\Mail\ReservationConfirmation;
use App\Mail\ReservationNotification;
use App\Models\Experience;
use App\Models\Reservation;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReservasController extends Controller
{
    public function index()
    {
        $experiences = Experience::where('active', true)->get();

        return view('reservas.index', compact('experiences'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'phone'           => 'required|string|max:50',
            'check_in'        => 'required|date_format:d/m/Y',
            'check_out'       => 'required|date_format:d/m/Y',
            'adults'          => 'required|integer|min:1|max:20',
            'children'        => 'nullable|integer|min:0|max:10',
            'children_ages'   => 'nullable|string|max:100',
            'experience_slug' => 'required|exists:experiences,slug',
            'message'         => 'nullable|string|max:2000',
        ]);

        $checkIn  = Carbon::createFromFormat('d/m/Y', $validated['check_in'])->startOfDay();
        $checkOut = Carbon::createFromFormat('d/m/Y', $validated['check_out'])->startOfDay();

        if (!$checkOut->isAfter($checkIn)) {
            return back()
                ->withErrors(['check_out' => 'O check-out deve ser posterior ao check-in.'])
                ->withInput();
        }

        $experience = Experience::where('slug', $validated['experience_slug'])->firstOrFail();

        $guests = (int) $validated['adults'] + (int) ($validated['children'] ?? 0);

        $notes = trim(
            ($validated['message'] ?? '') .
            (!empty($validated['children_ages']) ? "\nIdades das crianças: " . $validated['children_ages'] : '')
        );

        $reservation = Reservation::create([
            'experience_id' => $experience->id,
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'],
            'check_in'      => $checkIn->format('Y-m-d'),
            'check_out'     => $checkOut->format('Y-m-d'),
            'guests'        => $guests,
            'message'       => $notes ?: null,
            'status'        => 'pending',
        ]);

        $ownerEmail = Setting::get('email', config('mail.from.address'));

        try {
            Mail::to($reservation->email)->send(new ReservationConfirmation($reservation, $experience));
            Mail::to($ownerEmail)->send(new ReservationNotification($reservation, $experience));
        } catch (\Throwable $e) {
            Log::error('Reservation emails failed: ' . $e->getMessage());
        }

        return redirect()->route('reservas.sucesso')->with([
            'booking_name' => $reservation->name,
            'booking_exp'  => $experience->name_pt,
            'booking_in'   => $validated['check_in'],
            'booking_out'  => $validated['check_out'],
        ]);
    }

    public function sucesso()
    {
        if (!session()->has('booking_name')) {
            return redirect()->route('reservas');
        }

        return view('reservas.sucesso');
    }
}
