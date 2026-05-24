<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        Experience::upsert([
            [
                'slug'                  => 'imersiva',
                'name_pt'               => 'Experiência Imersiva',
                'name_en'               => 'Immersive Experience',
                'short_description_pt'  => 'Uma experiência que vai surpreender a cada esquina — perfeita para famílias, casais e grupos de amigos que procuram algo verdadeiramente único.',
                'short_description_en'  => 'An experience that will surprise you at every turn — perfect for families, couples and groups of friends looking for something truly unique.',
                'description_pt'        => 'A casa principal da Funtastic House é um universo à parte. Dois quartos temáticos (Quarto Estrelas e Quarto Arco-Íris), jardim privado, piscina e churrasqueira. Cada divisão foi desenhada para criar memórias: da Cozinha Jardim à Casa de Banho Mar, cada detalhe conta uma história. Uma experiência que vai surpreender a cada esquina.',
                'description_en'        => 'The main house of Funtastic House is a world apart. Two themed bedrooms (Stars Room and Rainbow Room), private garden, pool and barbecue. Every room was designed to create memories: from the Garden Kitchen to the Sea Bathroom, every detail tells a story. An experience that will surprise you at every turn.',
                'base_price'            => 120.00,
                'weekend_price'         => 145.00,
                'max_guests'            => 6,
                'bedrooms'              => 2,
                'active'                => true,
            ],
            [
                'slug'                  => 'spa',
                'name_pt'               => 'Experiência Spa',
                'name_en'               => 'Spa Experience',
                'short_description_pt'  => 'Desliga do mundo e recarrega energias. Esta casa mais intimista tem um pátio privado com jacuzzi onde te podes perder horas a olhar para o céu.',
                'short_description_en'  => 'Disconnect from the world and recharge. This more intimate house has a private patio with a jacuzzi where you can spend hours gazing at the sky.',
                'description_pt'        => 'A Experiência Spa foi pensada para quem quer silêncio, conforto e privacidade total. Dois quartos, pátio privado com jacuzzi e uma decoração que convida ao descanso. Sintra fica a 10 minutos — o melhor dos dois mundos: sossego e cultura à porta.',
                'description_en'        => 'The Spa Experience was designed for those who want silence, comfort and total privacy. Two bedrooms, private patio with jacuzzi and a decor that invites rest. Sintra is 10 minutes away — the best of both worlds: peace and culture at your doorstep.',
                'base_price'            => 90.00,
                'weekend_price'         => 110.00,
                'max_guests'            => 4,
                'bedrooms'              => 2,
                'active'                => true,
            ],
        ], ['slug'], [
            'name_pt', 'name_en', 'short_description_pt', 'short_description_en',
            'description_pt', 'description_en', 'base_price', 'weekend_price',
            'max_guests', 'bedrooms', 'active',
        ]);
    }
}
