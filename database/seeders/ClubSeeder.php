<?php

namespace Database\Seeders;

use App\Models\Club;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    public function run(): void
    {
        $clubs = [
            [
                'name' => 'Manchester United',
                'slug' => 'manchester-united',
                'description' => 'One of the most successful clubs in English football',
                'country' => 'England',
                'founded_year' => 1878,
            ],
            [
                'name' => 'FC Barcelona',
                'slug' => 'fc-barcelona',
                'description' => 'Spanish football club known for their tiki-taka style',
                'country' => 'Spain',
                'founded_year' => 1899,
            ],
            [
                'name' => 'Liverpool FC',
                'slug' => 'liverpool-fc',
                'description' => 'Historic English club with passionate fan base',
                'country' => 'England',
                'founded_year' => 1892,
            ],
            [
                'name' => 'Real Madrid',
                'slug' => 'real-madrid',
                'description' => 'Most successful club in UEFA Champions League history',
                'country' => 'Spain',
                'founded_year' => 1902,
            ],
            [
                'name' => 'Juventus',
                'slug' => 'juventus',
                'description' => 'Italian giants with a legacy of winning',
                'country' => 'Italy',
                'founded_year' => 1897,
            ],
            [
                'name' => 'Bayern Munich',
                'slug' => 'bayern-munich',
                'description' => 'Most successful German football club',
                'country' => 'Germany',
                'founded_year' => 1900,
            ],
            [
                'name' => 'Paris Saint-Germain',
                'slug' => 'paris-saint-germain',
                'description' => 'French champions with world-class players',
                'country' => 'France',
                'founded_year' => 1970,
            ],
            [
                'name' => 'Chelsea FC',
                'slug' => 'chelsea-fc',
                'description' => 'London-based club with modern success',
                'country' => 'England',
                'founded_year' => 1905,
            ],
        ];

        foreach ($clubs as $club) {
            Club::create($club);
        }
    }
}
