<?php

namespace Database\Seeders;

use App\Models\PoiCategory;
use Illuminate\Database\Seeder;

class PoiCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name_pt' => 'Palácios & Património', 'name_en' => 'Palaces & Heritage',       'icon' => 'fa-crown'],
            ['name_pt' => 'Praias',                 'name_en' => 'Beaches',                   'icon' => 'fa-umbrella-beach'],
            ['name_pt' => 'Mafra',                  'name_en' => 'Mafra',                     'icon' => 'fa-landmark'],
            ['name_pt' => 'Atividades',              'name_en' => 'Activities & Experiences',  'icon' => 'fa-person-biking'],
            ['name_pt' => 'Restaurantes',            'name_en' => 'Restaurants',               'icon' => 'fa-utensils'],
            ['name_pt' => 'Compras & Artesanato',   'name_en' => 'Shopping & Crafts',         'icon' => 'fa-bag-shopping'],
        ];

        foreach ($categories as $data) {
            PoiCategory::firstOrCreate(['name_pt' => $data['name_pt']], $data);
        }
    }
}
