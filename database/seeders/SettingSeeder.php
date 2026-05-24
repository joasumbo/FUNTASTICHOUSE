<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'email'           => 'hello@funtastichouse.pt',
            'phone'           => '+351 900 000 000',
            'whatsapp'        => '351900000000',
            'instagram_url'   => 'https://instagram.com/funtastichouse',
            'facebook_url'    => 'https://facebook.com/funtastichouse',
            'address'         => 'Sintra / Ericeira / Mafra, Portugal',
            'address_full'    => '',
            'maps_embed_url'  => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d24182!2d-9.3894!3d38.7879!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd1ecb49f52cca3d%3A0x30a92d6a95916ef6!2sSintra!5e0!3m2!1spt!2spt!4v1700000000000!5m2!1spt!2spt',
            'meta_title_pt'   => 'Funtastic House — Alojamento Temático perto de Sintra',
            'meta_title_en'   => 'Funtastic House — Themed Accommodation near Sintra',
            'meta_desc_pt'    => 'Alojamento local temático único perto de Sintra, Ericeira e Mafra. Duas experiências exclusivas: Imersiva e Spa.',
            'meta_desc_en'    => 'Unique themed local accommodation near Sintra, Ericeira and Mafra. Two exclusive experiences: Immersive and Spa.',
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
