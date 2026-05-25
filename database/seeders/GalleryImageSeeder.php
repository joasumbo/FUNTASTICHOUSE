<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

class GalleryImageSeeder extends Seeder
{
    public function run(): void
    {
        $imersiva = Experience::where('slug', 'imersiva')->first()?->id;
        $spa      = Experience::where('slug', 'spa')->first()?->id;

        $images = [
            [
                'experience_id' => $imersiva,
                'category'      => 'imersiva',
                'filename'      => 'images/rooms/room-1.jpg',
                'alt_pt'        => 'Experiência Imersiva',
                'alt_en'        => 'Immersive Experience',
                'order'         => 1,
            ],
            [
                'experience_id' => $imersiva,
                'category'      => 'imersiva',
                'filename'      => 'images/rooms/room-2.jpg',
                'alt_pt'        => 'Quarto Estrelas',
                'alt_en'        => 'Stars Room',
                'order'         => 2,
            ],
            [
                'experience_id' => $imersiva,
                'category'      => 'imersiva',
                'filename'      => 'images/rooms/room-3.jpg',
                'alt_pt'        => 'Quarto Arco-Íris',
                'alt_en'        => 'Rainbow Room',
                'order'         => 3,
            ],
            [
                'experience_id' => $imersiva,
                'category'      => 'imersiva',
                'filename'      => 'images/rooms/room-4.jpg',
                'alt_pt'        => 'Casa de Banho Fundo do Mar',
                'alt_en'        => 'Sea Bathroom',
                'order'         => 4,
            ],
            [
                'experience_id' => $imersiva,
                'category'      => 'interior',
                'filename'      => 'images/about.jpg',
                'alt_pt'        => 'Sala Principal',
                'alt_en'        => 'Living Room',
                'order'         => 5,
            ],
            [
                'experience_id' => $spa,
                'category'      => 'spa',
                'filename'      => 'images/spa/spa.jpg',
                'alt_pt'        => 'Experiência Spa',
                'alt_en'        => 'Spa Experience',
                'order'         => 6,
            ],
            [
                'experience_id' => $spa,
                'category'      => 'spa',
                'filename'      => 'images/spa/spa-about.jpg',
                'alt_pt'        => 'Jacuzzi',
                'alt_en'        => 'Jacuzzi',
                'order'         => 7,
            ],
            [
                'experience_id' => $spa,
                'category'      => 'spa',
                'filename'      => 'images/spa/spa-service-1.jpg',
                'alt_pt'        => 'Serviço Spa',
                'alt_en'        => 'Spa Service',
                'order'         => 8,
            ],
            [
                'experience_id' => $spa,
                'category'      => 'spa',
                'filename'      => 'images/spa/spa-service-2.jpg',
                'alt_pt'        => 'Tratamento Spa',
                'alt_en'        => 'Spa Treatment',
                'order'         => 9,
            ],
            [
                'experience_id' => null,
                'category'      => 'exterior',
                'filename'      => 'images/rooms/room-5.jpg',
                'alt_pt'        => 'Jardim Privado',
                'alt_en'        => 'Private Garden',
                'order'         => 10,
            ],
            [
                'experience_id' => null,
                'category'      => 'exterior',
                'filename'      => 'images/experience.jpg',
                'alt_pt'        => 'Exterior da Casa',
                'alt_en'        => 'House Exterior',
                'order'         => 11,
            ],
            [
                'experience_id' => null,
                'category'      => 'exterior',
                'filename'      => 'images/rooms/room-3-v.jpg',
                'alt_pt'        => 'Piscina',
                'alt_en'        => 'Swimming Pool',
                'order'         => 12,
            ],
        ];

        foreach ($images as $data) {
            GalleryImage::firstOrCreate(
                ['filename' => $data['filename']],
                array_merge($data, ['active' => true])
            );
        }
    }
}
