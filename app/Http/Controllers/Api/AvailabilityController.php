<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\JsonResponse;

class AvailabilityController extends Controller
{
    public function show(string $slug): JsonResponse
    {
        $experience = Experience::with('blockedDates')
            ->where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        return response()->json([
            'slug'          => $experience->slug,
            'prices'        => [
                'base'    => (float) $experience->base_price,
                'weekend' => (float) $experience->weekend_price,
            ],
            'blocked_dates' => $experience->blockedDates
                ->pluck('date')
                ->map(fn ($d) => $d->toDateString())
                ->values(),
        ]);
    }
}
