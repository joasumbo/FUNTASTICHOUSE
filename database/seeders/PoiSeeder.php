<?php

namespace Database\Seeders;

use App\Models\Poi;
use App\Models\PoiCategory;
use Illuminate\Database\Seeder;

class PoiSeeder extends Seeder
{
    public function run(): void
    {
        $palacios  = PoiCategory::where('name_pt', 'Palácios & Património')->value('id');
        $praias    = PoiCategory::where('name_pt', 'Praias')->value('id');
        $mafra     = PoiCategory::where('name_pt', 'Mafra')->value('id');
        $atividades = PoiCategory::where('name_pt', 'Atividades')->value('id');

        $pois = [
            // Palácios — Sintra
            [
                'poi_category_id' => $palacios,
                'name_pt'         => 'Palácio Nacional de Sintra',
                'name_en'         => 'National Palace of Sintra',
                'description_pt'  => 'Um dos palácios medievais mais bem preservados da Península Ibérica, no coração da vila de Sintra.',
                'description_en'  => 'One of the best-preserved medieval palaces on the Iberian Peninsula, in the heart of Sintra village.',
                'lat'             => 38.7978,
                'lng'             => -9.3906,
                'distance_km'     => 10.5,
                'active'          => true,
            ],
            [
                'poi_category_id' => $palacios,
                'name_pt'         => 'Palácio da Pena',
                'name_en'         => 'Pena Palace',
                'description_pt'  => 'Palácio romântico do século XIX no topo da Serra de Sintra, com vistas deslumbrantes sobre o Atlântico.',
                'description_en'  => '19th-century Romantic palace atop the Sintra hills, with stunning Atlantic views.',
                'lat'             => 38.7876,
                'lng'             => -9.3906,
                'distance_km'     => 12.0,
                'active'          => true,
            ],
            [
                'poi_category_id' => $palacios,
                'name_pt'         => 'Quinta da Regaleira',
                'name_en'         => 'Quinta da Regaleira',
                'description_pt'  => 'Propriedade misteriosa com palácio, capela e jardins repletos de simbolismo maçónico e templário.',
                'description_en'  => 'Mysterious estate with palace, chapel and gardens filled with Masonic and Templar symbolism.',
                'lat'             => 38.7939,
                'lng'             => -9.3983,
                'distance_km'     => 11.0,
                'active'          => true,
            ],
            [
                'poi_category_id' => $palacios,
                'name_pt'         => 'Castelo dos Mouros',
                'name_en'         => 'Moorish Castle',
                'description_pt'  => 'Fortaleza medieval do século VIII com muralhas que se estendem pela Serra de Sintra.',
                'description_en'  => '8th-century medieval fortress with walls stretching across the Sintra hills.',
                'lat'             => 38.7919,
                'lng'             => -9.3878,
                'distance_km'     => 11.5,
                'active'          => true,
            ],
            [
                'poi_category_id' => $palacios,
                'name_pt'         => 'Palácio de Monserrate',
                'name_en'         => 'Monserrate Palace',
                'description_pt'  => 'Palácio de estilo indo-mourisco rodeado por jardins botânicos únicos em Portugal.',
                'description_en'  => 'Indo-Moorish style palace surrounded by unique botanical gardens in Portugal.',
                'lat'             => 38.7892,
                'lng'             => -9.4306,
                'distance_km'     => 14.5,
                'active'          => true,
            ],
            // Praias — Ericeira
            [
                'poi_category_id' => $praias,
                'name_pt'         => 'Praia do Sul',
                'name_en'         => 'Praia do Sul',
                'description_pt'  => 'Praia urbana de Ericeira, ideal para banhos e para apreciar o pôr do sol atlântico.',
                'description_en'  => 'Ericeira\'s urban beach, ideal for swimming and watching the Atlantic sunset.',
                'lat'             => 38.9528,
                'lng'             => -9.4167,
                'distance_km'     => 6.0,
                'active'          => true,
            ],
            [
                'poi_category_id' => $praias,
                'name_pt'         => 'Praia dos Pescadores',
                'name_en'         => 'Fishermen\'s Beach',
                'description_pt'  => 'Pequena praia pitoresca no centro de Ericeira, com barcos coloridos e ambiente típico.',
                'description_en'  => 'Small picturesque beach in central Ericeira, with colourful boats and a typical atmosphere.',
                'lat'             => 38.9617,
                'lng'             => -9.4175,
                'distance_km'     => 7.0,
                'active'          => true,
            ],
            [
                'poi_category_id' => $praias,
                'name_pt'         => 'Ribeira de Ilhas',
                'name_en'         => 'Ribeira de Ilhas',
                'description_pt'  => 'Reserva Mundial de Surf, palco de etapas do campeonato mundial WSL. Uma das melhores ondas da Europa.',
                'description_en'  => 'World Surfing Reserve, host of WSL World Championship events. One of Europe\'s best waves.',
                'lat'             => 38.9831,
                'lng'             => -9.4244,
                'distance_km'     => 9.5,
                'active'          => true,
            ],
            [
                'poi_category_id' => $praias,
                'name_pt'         => 'Praia de São Julião',
                'name_en'         => 'São Julião Beach',
                'description_pt'  => 'Praia ampla e tranquila a norte de Ericeira, favorita de famílias e amantes do surf.',
                'description_en'  => 'Wide, peaceful beach north of Ericeira, a favourite with families and surf lovers.',
                'lat'             => 38.9722,
                'lng'             => -9.4311,
                'distance_km'     => 8.5,
                'active'          => true,
            ],
            // Mafra
            [
                'poi_category_id' => $mafra,
                'name_pt'         => 'Palácio Nacional de Mafra',
                'name_en'         => 'National Palace of Mafra',
                'description_pt'  => 'Palácio-convento barroco classificado pela UNESCO. Uma das maiores obras da arquitectura portuguesa do século XVIII.',
                'description_en'  => 'Baroque palace-convent classified by UNESCO. One of the greatest works of 18th-century Portuguese architecture.',
                'lat'             => 38.9376,
                'lng'             => -9.3289,
                'distance_km'     => 8.0,
                'active'          => true,
            ],
            [
                'poi_category_id' => $mafra,
                'name_pt'         => 'Tapada Nacional de Mafra',
                'name_en'         => 'Tapada Nacional de Mafra',
                'description_pt'  => 'Antiga reserva de caça real com 827 hectares de floresta, percursos pedestres e fauna diversificada.',
                'description_en'  => 'Former royal hunting reserve with 827 hectares of forest, walking trails and diverse wildlife.',
                'lat'             => 38.9389,
                'lng'             => -9.3331,
                'distance_km'     => 8.5,
                'active'          => true,
            ],
        ];

        foreach ($pois as $data) {
            Poi::firstOrCreate(
                ['name_pt' => $data['name_pt']],
                $data
            );
        }
    }
}
