<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Buyer;
use App\Models\Billboard;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class PerfectOrderSeeder extends Seeder
{
    protected $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    public function run(): void
    {
        $buyers = Buyer::pluck('id')->toArray();
        $billboards = Billboard::with('vendor')->get();

        foreach ($billboards as $billboard) {
            // Create some orders for each billboard
            $numOrders = rand(0, 3);
            for ($i = 0; $i < $numOrders; $i++) {
                Order::create([
                    'vendor_id' => $billboard->vendor_id,
                    'buyer_id' => $this->faker->randomElement($buyers),
                    'billboard_id' => $billboard->id,
                    'description' => $this->faker->paragraph,
                    'number' => $this->faker->numberBetween(10000000, 99999999),
                    'amount' => $billboard->price,
                    'time' => $this->faker->numberBetween(1, 12),
                    'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
                    'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}