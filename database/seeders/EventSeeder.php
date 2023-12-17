<?php

namespace Database\Seeders;

use App\Models\Event;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $images = [
            'amnoel-gomes-ny.png',
            'beicola.png',
            'bruno-diferente.png',
            'cazalbe.png',
            'cleitonrasta.jpeg',
            'gasias.png',
            'irineu.jpeg',
            'joaocleber.png',
            'lgbt.jpeg',
            'manoel-gomes.png',
            'nadson.png',
            'pedro-nove-bolado.png',
            'pele.png',
            'pool.jpg',
            'silfarley.png',
            'tamandua.jpeg',
            'tiririca.jpeg',
            'tiririca.jpg',
            'vin-diesel.png',
            'xand-aviao.png',
        ];

        $eventNames = [
            "Encontro dos Memes Vivos",
            "Festa do Gato Tecladista",
            "Noite dos Doge Aventureiros",
            "Batalha de GIFs",
            "Maratona Pepe Sapiens",
            "Carnaval do Nyan Cat",
            "Sarau dos Rage Comics",
            "Reunião dos Kermit Pensativos",
            "Festival Saltitante de Frogs",
            "Convenção de Troll Faces",
            "Baile dos Dank Memes",
            "Jantar dos Forever Alone",
            "Desfile de Grumpy Cats",
            "Rave do Rick Roll",
            "Encontro Bad Luck Brian",
            "Festival Overly Attached Girlfriend",
            "Conferência Success Kid",
            "Acampamento Distracted Boyfriend",
            "Retiro dos Philosoraptors",
            "Maratona dos Hide the Pain Harold",
            "Noite do Scumbag Steve",
            "Festa do Doge Sábio",
            "Jamboree do Confused Nick Young",
            "Feira de Memes da Velha Guarda",
            "Carnaval dos Dancing Babies",
            "Encontro dos Memes Clássicos",
            "Noite dos Memes Inesquecíveis",
            "Baile dos Memes Virais",
            "Gala de Memes Timeless",
            "Roda de Memes Nostálgicos",
            "Picnic de Memes Atemporais",
            "Festival de Memes Inovadores",
            "Cerimônia de Memes Lendários",
            "Torneio de Memes Épicos",
            "Convenção de Memes Históricos",
            "Festa dos Drake Hotline Bling",
            "Festival de Distracted Boyfriend",
            "Encontro de Harlem Shake",
            "Festa dos Memes de Gatos",
            "Carnaval dos Memes de Cães",
            "Noite do Meme do Bebê Yoda",
            "Baile dos Memes da Berenice",
            "Festival do Meme do John Travolta Confuso",
            "Gala do Meme do DiCaprio Brindando",
            "Encontro do Meme da Mulher Gritando no Gato",
            "Festa do Meme do Spongebob",
            "Festival do Meme do Kermit Tomando Chá",
            "Carnaval do Meme do Patrick Estrela",
            "Encontro do Meme do Gato que Quer Cheeseburger",
            "Noite dos Memes dos Simpsons"
        ];

        for ($i = 0; $i < 50; $i++) { // Adjust the number of events to seed as needed
            $startDate = $faker->dateTimeBetween('now', '+1 year');
            $endDate = (clone $startDate)->modify('+' . rand(0, 5) . ' days');

            Event::create([
                'id' => Str::uuid(),
                'name' => $eventNames[$i],
                'description' => $faker->paragraph,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'start_tickets_qty' => $faker->numberBetween(100, 1000),
                'current_tickets_qty' => $faker->numberBetween(0, 1000),
                'current_price' => $faker->randomFloat(2, 0, 500),
                'address' => $faker->address,
                'image_url' => 'events/' . $faker->randomElement($images),
                'category_id' => $faker->randomElement([
                    '836080d2-63d0-4917-97ac-c404614f44be',
                    '88a5890b-5d41-440e-a330-1e0c049ffb86',
                    '4447616f-c7a9-48a4-9f0f-0c12c6b988da'
                ]),
                'city_id' => $faker->randomElement([
                    'cccccccc-cccc-cccc-cccc-cccccccccccc',
                    'dddddddd-dddd-dddd-dddd-dddddddddddd',
                    'eeeeeeee-eeee-eeee-eeee-eeeeeeeeeeee',
                    'ffffffff-ffff-ffff-ffff-ffffffffffff',
                    '11111111-1111-1111-1111-111111111111'
                ]),
                'owner_id' => '88888888-8888-8888-8888-888888888888',
                'status' => 'UPCOMING', // Assuming these are the possible statuses
                'created_at' => now(),
            ]);
        }
    }
}