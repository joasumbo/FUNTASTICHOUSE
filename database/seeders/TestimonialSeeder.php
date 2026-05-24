<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'author_name'     => 'Ana & Ricardo S.',
                'author_location' => 'Família de Lisboa',
                'content_pt'      => '"Nunca ficámos em nenhum lugar assim. O teto estrelado no quarto é simplesmente mágico — os nossos filhos não queriam sair. E o quadro dos doces? Uma surpresa que vão falar durante anos!"',
                'content_en'      => '"We\'ve never stayed anywhere like this. The starry ceiling in the room is simply magical — our kids didn\'t want to leave. And the candy wall? A surprise they\'ll talk about for years!"',
                'rating'          => 5,
                'active'          => true,
                'order'           => 1,
            ],
            [
                'author_name'     => 'Marta & João P.',
                'author_location' => 'Casal do Porto',
                'content_pt'      => '"A Experiência Spa foi perfeita para o nosso aniversário. Jacuzzi privado, silêncio e uma casa cuidada ao pormenor. Acordar ali e saber que Sintra fica a 10 minutos é um luxo enorme."',
                'content_en'      => '"The Spa Experience was perfect for our anniversary. Private jacuzzi, silence and a meticulously maintained house. Waking up there knowing Sintra is 10 minutes away is an enormous luxury."',
                'rating'          => 5,
                'active'          => true,
                'order'           => 2,
            ],
            [
                'author_name'     => 'Sophie M.',
                'author_location' => 'Viajante francesa',
                'content_pt'      => '"A casa de banho com o lavatório em concha é uma obra de arte. Cada divisão é uma surpresa nova. O som dos passarinhos ao entrar é um detalhe que não esquece. Única em Portugal."',
                'content_en'      => '"The bathroom with the shell sink is a work of art. Every room is a new surprise. The sound of birds when you enter is a detail you don\'t forget. Unique in Portugal."',
                'rating'          => 5,
                'active'          => true,
                'order'           => 3,
            ],
        ];

        foreach ($testimonials as $data) {
            Testimonial::firstOrCreate(
                ['author_name' => $data['author_name']],
                $data
            );
        }
    }
}
