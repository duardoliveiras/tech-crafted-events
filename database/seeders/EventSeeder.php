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
            'img1.jpeg',
            'img2.png',
            'img3.png',
            'img4.jpeg',
            'img5.jpeg',
            'img6.jpeg',
            'img7.jpg',
            'img8.png',
            'img9.jpeg',
            'pele.png',
            'pool.jpg',
            'silfarley.png',
            //            'tiririca.jpeg'
        ];

        $eventNames = [
            "Conference on Innovation and Technology",
            "Music Festival Under the Stars",
            "International Business Expo",
            "Art and Wine Gala",
            "Science and Technology Symposium",
            "Charity Fundraising Gala",
            "Film Premiere and Red Carpet Event",
            "Global Leadership Summit",
            "Fashion Week Showcase",
            "Food and Wine Tasting",
            "Health and Wellness Retreat",
            "Environmental Sustainability Conference",
            "Sports Extravaganza",
            "Tech Startup Pitch Competition",
            "Cultural Diversity Fair",
            "Comedy Show and Stand-up Night",
            "Literary Arts Festival",
            "Automotive Trade Show",
            "Travel and Adventure Expo",
            "Educational Seminar Series",
            "Design and Architecture Exhibition",
            "Fitness and Wellness Expo",
            "Entrepreneurship Bootcamp",
            "Historical Reenactment Festival",
            "Wine and Cheese SoirÃ©e",
            "Gaming and Esports Tournament",
            "Pet Adoption and Rescue Event",
            "Music and Arts Showcase",
            "Home and Garden Expo",
            "International Human Rights Summit"
        ];

        for ($i = 0; $i < 30; $i++) { // Adjust the number of events to seed as needed
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
                'image_url' => $faker->randomElement($images),
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