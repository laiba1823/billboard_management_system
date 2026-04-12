<?php

namespace Database\Seeders;

use App\Models\Buyer;
use App\Models\Vendor;
use App\Models\Transactions;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class PerfectTransactionsSeeder extends Seeder
{
    protected $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    public function run(): void
    {
        $buyers = Buyer::pluck('id')->toArray();
        $vendors = Vendor::pluck('id')->toArray();
        $orders = Order::where('status', 'completed')->get();

        // Create transactions for completed orders
        foreach ($orders as $order) {
            Transactions::create([
                'number' => $this->faker->unique()->numberBetween(10000000, 99999999),
                'from' => $order->buyer_id,
                'to' => $order->vendor_id,
                'amount' => $order->amount,
                'status' => 'paid',
                'created_at' => $this->faker->dateTimeBetween($order->created_at, 'now'),
                'updated_at' => now(),
            ]);
        }

        // Create some additional random transactions
        for ($i = 0; $i < 50; $i++) {
            Transactions::create([
                'number' => $this->faker->unique()->numberBetween(10000000, 99999999),
                'from' => $this->faker->randomElement($buyers),
                'to' => $this->faker->randomElement($vendors),
                'amount' => $this->faker->numberBetween(10, 500),
                'status' => $this->faker->randomElement(['paid', 'pending', 'cancelled']),
                'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}