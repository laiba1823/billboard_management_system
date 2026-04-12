<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class PerfectCategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::truncate();

        $categories = [
            ['name' => 'Karachi', 'description' => 'The largest city in Pakistan, a major economic hub with diverse cultures and bustling markets.'],
            ['name' => 'Lahore', 'description' => 'The cultural capital of Pakistan, known for its historical sites, food, and vibrant arts scene.'],
            ['name' => 'Islamabad', 'description' => 'The capital city, featuring modern architecture, green spaces, and government buildings.'],
            ['name' => 'Rawalpindi', 'description' => 'A city adjacent to Islamabad, known for its military history and commercial activities.'],
            ['name' => 'Faisalabad', 'description' => 'An industrial city, often called the Manchester of Pakistan for its textile industry.'],
            ['name' => 'Multan', 'description' => 'Known as the City of Saints, famous for its shrines, mangoes, and historical forts.'],
            ['name' => 'Peshawar', 'description' => 'A historic city near the Afghan border, known for its bazaars and cultural heritage.'],
            ['name' => 'Quetta', 'description' => 'The capital of Balochistan, known for its fruits, mountains, and strategic location.'],
            ['name' => 'Sialkot', 'description' => 'Famous for sports goods manufacturing and surgical instruments.'],
            ['name' => 'Gujranwala', 'description' => 'An industrial city known for its textile and leather industries.'],
            ['name' => 'Hyderabad', 'description' => 'Known for its historical monuments and rich cultural heritage.'],
            ['name' => 'Bahawalpur', 'description' => 'Famous for its palaces, forts, and the Cholistan Desert nearby.'],
            ['name' => 'Sargodha', 'description' => 'Known as the City of Eagles, an agricultural and educational hub.'],
            ['name' => 'Sukkur', 'description' => 'Located on the Indus River, known for its barrage and historical sites.'],
            ['name' => 'Larkana', 'description' => 'Birthplace of several Pakistani leaders, known for its rice production.'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => \Illuminate\Support\Str::slug($category['name']),
                'description' => $category['description'],
            ]);
        }
    }
}