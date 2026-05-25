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
                'category'      => 'quarto-estrelas',
                'filename'      => 'images/rooms/room-1.jpg',
                'alt_pt'        => 'Quarto Estrelas — teto estrelado',
                'alt_en'        => 'Stars Room — starry ceiling',
                'order'         => 1,
            ],
            [
                'experience_id' => $imersiva,
                'category'      => 'quarto-estrelas',
                'filename'      => 'images/rooms/room-2.jpg',
                'alt_pt'        => 'Quarto Estrelas',
                'alt_en'        => 'Stars Room',
                'order'         => 2,
            ],
            [
                'experience_id' => $imersiva,
                'category'      => 'quarto-arco-iris',
                'filename'      => 'images/rooms/room-3.jpg',
                'alt_pt'        => 'Quarto Arco-Íris',
                'alt_en'        => 'Rainbow Room',
                'order'         => 3,
            ],
            [
                'experience_id' => $imersiva,
                'category'      => 'casa-de-banho',
                'filename'      => 'images/rooms/room-4.jpg',
                'alt_pt'        => 'Casa de Banho — decoração fundo do mar',
                'alt_en'        => 'Bathroom — ocean-themed décor',
                'order'         => 4,
            ],
            [
                'experience_id' => $imersiva,
                'category'      => 'cozinha-sala',
                'filename'      => 'images/about.jpg',
                'alt_pt'        => 'Cozinha & Sala — temática jardim',
                'alt_en'        => 'Kitchen & Lounge — garden theme',
                'order'         => 5,
            ],
            [
                'experience_id' => $spa,
                'category'      => 'quartos-spa',
                'filename'      => 'images/spa/spa.jpg',
                'alt_pt'        => 'Quartos Spa',
                'alt_en'        => 'Spa Rooms',
                'order'         => 6,
            ],
            [
                'experience_id' => $spa,
                'category'      => 'jacuzzi-exterior',
                'filename'      => 'images/spa/spa-about.jpg',
                'alt_pt'        => 'Jacuzzi privado',
                'alt_en'        => 'Private jacuzzi',
                'order'         => 7,
            ],
            [
                'experience_id' => $spa,
                'category'      => 'quartos-spa',
                'filename'      => 'images/spa/spa-service-1.jpg',
                'alt_pt'        => 'Quarto Spa',
                'alt_en'        => 'Spa Room',
                'order'         => 8,
            ],
            [
                'experience_id' => $spa,
                'category'      => 'jacuzzi-exterior',
                'filename'      => 'images/spa/spa-service-2.jpg',
                'alt_pt'        => 'Pátio com jacuzzi',
                'alt_en'        => 'Patio with jacuzzi',
                'order'         => 9,
            ],
            [
                'experience_id' => $imersiva,
                'category'      => 'jardim-exterior',
                'filename'      => 'images/rooms/room-5.jpg',
                'alt_pt'        => 'Jardim privado',
                'alt_en'        => 'Private garden',
                'order'         => 10,
            ],
            [
                'experience_id' => $imersiva,
                'category'      => 'jardim-exterior',
                'filename'      => 'images/experience.jpg',
                'alt_pt'        => 'Exterior da casa',
                'alt_en'        => 'House exterior',
                'order'         => 11,
            ],
            [
                'experience_id' => $imersiva,
                'category'      => 'jardim-exterior',
                'filename'      => 'images/rooms/room-3-v.jpg',
                'alt_pt'        => 'Piscina privada',
                'alt_en'        => 'Private swimming pool',
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
