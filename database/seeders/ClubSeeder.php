<?php

namespace Database\Seeders;

use App\Models\Club;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    public function run(): void
    {
        $clubs = [
            // Premier League (England)
            [
                'name' => 'Manchester United',
                'slug' => 'manchester-united',
                'description' => 'The Red Devils of the Premier League.',
                'country' => 'England',
                'founded_year' => 1878,
                'logo' => 'https://media.api-sports.io/football/teams/33.png'
            ],
            [
                'name' => 'Manchester City',
                'slug' => 'manchester-city',
                'description' => 'The Citizens.',
                'country' => 'England',
                'founded_year' => 1880,
                'logo' => 'https://media.api-sports.io/football/teams/50.png'
            ],
            [
                'name' => 'Liverpool FC',
                'slug' => 'liverpool-fc',
                'description' => 'You Will Never Walk Alone. Merseyside legends.',
                'country' => 'England',
                'founded_year' => 1892,
                'logo' => 'https://media.api-sports.io/football/teams/40.png'
            ],
            [
                'name' => 'Arsenal FC',
                'slug' => 'arsenal-fc',
                'description' => 'The Gunners.',
                'country' => 'England',
                'founded_year' => 1886,
                'logo' => 'https://media.api-sports.io/football/teams/42.png'
            ],
            [
                'name' => 'Chelsea FC',
                'slug' => 'chelsea-fc',
                'description' => 'The Blues of Stamford Bridge.',
                'country' => 'England',
                'founded_year' => 1905,
                'logo' => 'https://media.api-sports.io/football/teams/49.png'
            ],
            [
                'name' => 'Tottenham Hotspur',
                'slug' => 'tottenham-hotspur',
                'description' => 'The Spurs.',
                'country' => 'England',
                'founded_year' => 1882,
                'logo' => 'https://media.api-sports.io/football/teams/47.png'
            ],
            [
                'name' => 'Aston Villa',
                'slug' => 'aston-villa',
                'description' => 'The Villans.',
                'country' => 'England',
                'founded_year' => 1874,
                'logo' => 'https://media.api-sports.io/football/teams/66.png'
            ],
            [
                'name' => 'Newcastle United',
                'slug' => 'newcastle-united',
                'description' => 'The Magpies.',
                'country' => 'England',
                'founded_year' => 1892,
                'logo' => 'https://media.api-sports.io/football/teams/34.png'
            ],
            // La Liga (Spain)
            [
                'name' => 'FC Barcelona',
                'slug' => 'fc-barcelona',
                'description' => 'Mes que un club. Catalan giants of La Liga.',
                'country' => 'Spain',
                'founded_year' => 1899,
                'logo' => 'https://media.api-sports.io/football/teams/529.png'
            ],
            [
                'name' => 'Real Madrid',
                'slug' => 'real-madrid',
                'description' => 'Los Blancos. The kings of Europe.',
                'country' => 'Spain',
                'founded_year' => 1902,
                'logo' => 'https://media.api-sports.io/football/teams/541.png'
            ],
            [
                'name' => 'Atletico Madrid',
                'slug' => 'atletico-madrid',
                'description' => 'Los Colchoneros.',
                'country' => 'Spain',
                'founded_year' => 1903,
                'logo' => 'https://media.api-sports.io/football/teams/530.png'
            ],
            [
                'name' => 'Real Sociedad',
                'slug' => 'real-sociedad',
                'description' => 'Erreala.',
                'country' => 'Spain',
                'founded_year' => 1909,
                'logo' => 'https://media.api-sports.io/football/teams/548.png'
            ],
            [
                'name' => 'Athletic Club',
                'slug' => 'athletic-club',
                'description' => 'Los Leones.',
                'country' => 'Spain',
                'founded_year' => 1898,
                'logo' => 'https://media.api-sports.io/football/teams/531.png'
            ],
            [
                'name' => 'Girona FC',
                'slug' => 'girona-fc',
                'description' => 'Blanquivermells.',
                'country' => 'Spain',
                'founded_year' => 1930,
                'logo' => 'https://media.api-sports.io/football/teams/547.png'
            ],
            // Serie A (Italy)
            [
                'name' => 'Juventus',
                'slug' => 'juventus',
                'description' => 'La Vecchia Signora. Italian Serie A masters.',
                'country' => 'Italy',
                'founded_year' => 1897,
                'logo' => 'https://media.api-sports.io/football/teams/496.png'
            ],
            [
                'name' => 'Inter Milan',
                'slug' => 'inter-milan',
                'description' => 'I Nerazzurri.',
                'country' => 'Italy',
                'founded_year' => 1908,
                'logo' => 'https://media.api-sports.io/football/teams/505.png'
            ],
            [
                'name' => 'AC Milan',
                'slug' => 'ac-milan',
                'description' => 'I Rossoneri.',
                'country' => 'Italy',
                'founded_year' => 1899,
                'logo' => 'https://media.api-sports.io/football/teams/489.png'
            ],
            [
                'name' => 'SSC Napoli',
                'slug' => 'ssc-napoli',
                'description' => 'Gli Azzurri.',
                'country' => 'Italy',
                'founded_year' => 1926,
                'logo' => 'https://media.api-sports.io/football/teams/492.png'
            ],
            [
                'name' => 'AS Roma',
                'slug' => 'as-roma',
                'description' => 'I Giallorossi.',
                'country' => 'Italy',
                'founded_year' => 1927,
                'logo' => 'https://media.api-sports.io/football/teams/497.png'
            ],
            [
                'name' => 'SS Lazio',
                'slug' => 'ss-lazio',
                'description' => 'I Biancocelesti.',
                'country' => 'Italy',
                'founded_year' => 1900,
                'logo' => 'https://media.api-sports.io/football/teams/487.png'
            ],
            [
                'name' => 'Atalanta BC',
                'slug' => 'atalanta-bc',
                'description' => 'La Dea.',
                'country' => 'Italy',
                'founded_year' => 1907,
                'logo' => 'https://media.api-sports.io/football/teams/499.png'
            ],
            // Bundesliga (Germany)
            [
                'name' => 'Bayern Munich',
                'slug' => 'bayern-munich',
                'description' => 'The Bavarian powerhouse of the Bundesliga.',
                'country' => 'Germany',
                'founded_year' => 1900,
                'logo' => 'https://media.api-sports.io/football/teams/157.png'
            ],
            [
                'name' => 'Borussia Dortmund',
                'slug' => 'borussia-dortmund',
                'description' => 'Die Schwarzgelben.',
                'country' => 'Germany',
                'founded_year' => 1909,
                'logo' => 'https://media.api-sports.io/football/teams/165.png'
            ],
            [
                'name' => 'Bayer Leverkusen',
                'slug' => 'bayer-leverkusen',
                'description' => 'Die Werkself.',
                'country' => 'Germany',
                'founded_year' => 1904,
                'logo' => 'https://media.api-sports.io/football/teams/168.png'
            ],
            [
                'name' => 'RB Leipzig',
                'slug' => 'rb-leipzig',
                'description' => 'Die Roten Bullen.',
                'country' => 'Germany',
                'founded_year' => 2009,
                'logo' => 'https://media.api-sports.io/football/teams/173.png'
            ],
            [
                'name' => 'VfB Stuttgart',
                'slug' => 'vfb-stuttgart',
                'description' => 'Die Schwaben.',
                'country' => 'Germany',
                'founded_year' => 1893,
                'logo' => 'https://media.api-sports.io/football/teams/172.png'
            ],
            // Ligue 1 (France)
            [
                'name' => 'Paris Saint-Germain',
                'slug' => 'paris-saint-germain',
                'description' => 'The stars of Ligue 1 in the heart of Paris.',
                'country' => 'France',
                'founded_year' => 1970,
                'logo' => 'https://media.api-sports.io/football/teams/85.png'
            ],
            [
                'name' => 'Olympique Marseille',
                'slug' => 'olympique-marseille',
                'description' => 'Les Phoceens.',
                'country' => 'France',
                'founded_year' => 1899,
                'logo' => 'https://media.api-sports.io/football/teams/81.png'
            ],
            [
                'name' => 'Olympique Lyon',
                'slug' => 'olympique-lyon',
                'description' => 'Les Gones.',
                'country' => 'France',
                'founded_year' => 1950,
                'logo' => 'https://media.api-sports.io/football/teams/80.png'
            ],
            [
                'name' => 'AS Monaco',
                'slug' => 'as-monaco',
                'description' => 'Les Monegasques.',
                'country' => 'France',
                'founded_year' => 1924,
                'logo' => 'https://media.api-sports.io/football/teams/91.png'
            ],
        ];

        foreach ($clubs as $club) {
            Club::updateOrCreate(['slug' => $club['slug']], $club);
        }
    }
}
