<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarCategory;

class CarCategorySeeder extends Seeder
{
    /**
     * Tworzy kategorie samochodów
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sedan',
                'description' => 'Samochody osobowe z oddzielną komorą bagażową, idealne na dłuższe trasy.'
            ],
            [
                'name' => 'SUV',
                'description' => 'Sport Utility Vehicle - samochody terenowe o zwiększonym prześwicie, idealne na każdą drogę.'
            ],
            [
                'name' => 'Hatchback',
                'description' => 'Kompaktowe samochody miejskie z tylną klapą, oszczędne i manewrowe.'
            ],
            [
                'name' => 'Coupe',
                'description' => 'Sportowe dwudrzwiowe samochody o dynamicznej sylwetce.'
            ],
            [
                'name' => 'Kombi',
                'description' => 'Samochody rodzinne z dużym bagażnikiem, praktyczne i przestronne.'
            ],
        ];

        foreach ($categories as $category) {
            CarCategory::create($category);
        }
    }
}
