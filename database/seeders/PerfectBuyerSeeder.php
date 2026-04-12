<?php

namespace Database\Seeders;

use App\Models\BankAccounts;
use App\Models\Buyer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;

class PerfectBuyerSeeder extends Seeder
{
    protected $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $buyer = Buyer::create([
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'img' => 'img/profile-pictures/default.svg',
                'password' => Hash::make('password'),
                'address' => $this->faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create Bank Account for buyer
            BankAccounts::create([
                'user_type' => 'buyer',
                'user_id' => $buyer->id,
                'current_balance' => $this->faker->numberBetween(100, 10000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}