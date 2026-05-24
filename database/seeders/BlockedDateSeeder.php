<?php

namespace Database\Seeders;

use App\Models\BlockedDate;
use App\Models\Experience;
use Illuminate\Database\Seeder;

class BlockedDateSeeder extends Seeder
{
    public function run(): void
    {
        $imersiva = Experience::where('slug', 'imersiva')->value('id');
        $spa      = Experience::where('slug', 'spa')->value('id');

        $dates = [
            $imersiva => [
                '2025-07-08', '2025-07-09', '2025-07-10', '2025-07-11',
                '2025-07-12', '2025-07-13', '2025-07-21', '2025-07-22',
                '2025-08-01', '2025-08-02', '2025-08-03', '2025-08-15', '2025-08-16',
            ],
            $spa => [
                '2025-07-14', '2025-07-15', '2025-07-16', '2025-07-17',
                '2025-07-28', '2025-07-29', '2025-07-30',
                '2025-08-08', '2025-08-09', '2025-08-10', '2025-08-11',
            ],
        ];

        foreach ($dates as $experienceId => $days) {
            foreach ($days as $date) {
                BlockedDate::firstOrCreate(
                    ['experience_id' => $experienceId, 'date' => $date]
                );
            }
        }
    }
}
