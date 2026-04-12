<?php

namespace Database\Seeders;

use App\Models\BankAccounts;
use App\Models\Vendor;
use App\Models\Billboard;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class PerfectVendorSeeder extends Seeder
{
    protected $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    public function run(): void
    {
        $categories = Category::pluck('id')->toArray();

        for ($i = 1; $i <= 10; $i++) {
            $vendor = Vendor::create([
                'name' => $this->faker->company,
                'email' => $this->faker->unique()->safeEmail,
                'img' => 'img/profile-pictures/default.svg',
                'password' => Hash::make('password'),
                'about' => $this->faker->paragraph,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create Bank Account for vendor
            BankAccounts::create([
                'user_type' => 'vendor',
                'user_id' => $vendor->id,
                'current_balance' => $this->faker->numberBetween(500, 50000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create billboards for vendor
            for ($j = 1; $j <= rand(3, 8); $j++) {
                Billboard::create([
                    'vendor_id' => $vendor->id,
                    'title' => $this->faker->sentence,
                    'description' => $this->faker->paragraph,
                    'category_id' => $this->faker->randomElement($categories),
                    'price' => $this->faker->numberBetween(50, 1000),
                    'images' => [
                        'billboard-images/default/0_default.png',
                        'billboard-images/default/1_default.png',
                    ],
                    'uuid' => Str::uuid(),
                    'status' => $this->faker->randomElement(['active', 'disabled']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}