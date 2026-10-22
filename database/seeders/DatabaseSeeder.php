<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Status;
use App\Models\Thana;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        $admin = AdminUser::firstOrCreate(
            ['email' => 'admin@shop.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Statuses
        $statuses = [
            ['tag' => 'active', 'name' => 'Active'],
            ['tag' => 'inactive', 'name' => 'Inactive'],
            ['tag' => 'pending', 'name' => 'Pending'],
            ['tag' => 'processing', 'name' => 'Processing'],
            ['tag' => 'shipped', 'name' => 'Shipped'],
            ['tag' => 'delivered', 'name' => 'Delivered'],
            ['tag' => 'cancelled', 'name' => 'Cancelled'],
        ];
        foreach ($statuses as $st) {
            Status::firstOrCreate(['tag' => $st['tag']], $st);
        }

    }
}
