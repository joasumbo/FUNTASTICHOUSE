<?php

namespace App\Http\Controllers;

use App\Models\Experience;

class ReservasController extends Controller
{
    public function index()
    {
        $experiences = Experience::with('blockedDates')->where('active', true)->get();

        return view('reservas.index', compact('experiences'));
    }
}
