<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;

class ReservaController extends Controller
{
    public function show(Reservation $reservation)
    {
        $reservation->load('experience');
        return view('admin.reservas.show', compact('reservation'));
    }
}
