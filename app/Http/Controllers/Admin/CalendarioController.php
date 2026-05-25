<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedDate;
use App\Models\Experience;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    public function index()
    {
        $experiences = Experience::orderBy('name_pt')->get();
        return view('admin.calendario.index', compact('experiences'));
    }

    public function data(Request $request)
    {
        $request->validate([
            'experience_id' => 'required|exists:experiences,id',
            'year'          => 'required|integer|min:2020|max:2035',
            'month'         => 'required|integer|min:1|max:12',
        ]);

        $expId = (int) $request->experience_id;
        $year  = (int) $request->year;
        $month = (int) $request->month;

        $start = Carbon::create($year, $month, 1)->startOfDay();
        $end   = $start->copy()->endOfMonth()->endOfDay();

        $blocked = BlockedDate::where('experience_id', $expId)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->pluck('date')
            ->map(fn ($d) => Carbon::parse($d)->format('Y-m-d'))
            ->values()
            ->toArray();

        $reservations = Reservation::where('experience_id', $expId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('check_in', '<', $end->toDateString())
            ->where('check_out', '>', $start->toDateString())
            ->get();

        $resMap = [];
        foreach ($reservations as $res) {
            $ci  = Carbon::parse($res->check_in);
            $co  = Carbon::parse($res->check_out);
            $cur = $ci->copy();
            while ($cur->lt($co)) {
                $ds = $cur->format('Y-m-d');
                $resMap[$ds] = [
                    'id'     => $res->id,
                    'name'   => $res->name,
                    'status' => $res->status,
                ];
                $cur->addDay();
            }
        }

        return response()->json([
            'blocked'      => $blocked,
            'reservations' => $resMap,
        ]);
    }

    public function block(Request $request)
    {
        $request->validate([
            'experience_id' => 'required|exists:experiences,id',
            'dates'         => 'required|array|min:1|max:31',
            'dates.*'       => 'date',
        ]);

        $count = 0;
        foreach ($request->dates as $date) {
            BlockedDate::firstOrCreate([
                'experience_id' => $request->experience_id,
                'date'          => $date,
            ]);
            $count++;
        }

        return response()->json(['success' => true, 'count' => $count]);
    }

    public function unblock(Request $request)
    {
        $request->validate([
            'experience_id' => 'required|exists:experiences,id',
            'dates'         => 'required|array|min:1',
            'dates.*'       => 'date',
        ]);

        $deleted = BlockedDate::where('experience_id', $request->experience_id)
            ->whereIn('date', $request->dates)
            ->delete();

        return response()->json(['success' => true, 'count' => $deleted]);
    }
}
