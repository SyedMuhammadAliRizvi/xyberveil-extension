<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic Plan',
                'slug' => Str::slug('Basic Plan'),
                'description' => 'A basic plan for starters.',
                'original_price' => 19.99,
                'discount_price' => 14.99,
                'currency' => 'USD',
                'is_active' => true,
                'is_best_deal' => false,
                'trial_period' => 7,
                'trial_interval' => 'day',
                'invoice_period' => 1,
                'invoice_interval' => 'month',
                'grace_period' => 3,
                'grace_interval' => 'day',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pro Plan',
                'slug' => Str::slug('Pro Plan'),
                'description' => 'Perfect for professionals who need more features.',
                'original_price' => 49.99,
                'discount_price' => 39.99,
                'currency' => 'USD',
                'is_active' => true,
                'is_best_deal' => true,
                'trial_period' => 14,
                'trial_interval' => 'day',
                'invoice_period' => 1,
                'invoice_interval' => 'month',
                'grace_period' => 7,
                'grace_interval' => 'day',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Annual Plan',
                'slug' => Str::slug('Annual Plan'),
                'description' => 'Save more with an annual subscription.',
                'original_price' => 499.99,
                'discount_price' => 399.99,
                'currency' => 'USD',
                'is_active' => true,
                'is_best_deal' => false,
                'trial_period' => 30,
                'trial_interval' => 'day',
                'invoice_period' => 1,
                'invoice_interval' => 'year',
                'grace_period' => 14,
                'grace_interval' => 'day',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('plans')->insert($plans);
    }
}
