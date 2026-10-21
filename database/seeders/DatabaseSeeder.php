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

        // 3. Thana
        $thanas = [
            ['name' => 'Bogra Sadar', 'division_id' => 1]
        ];
        foreach ($thanas as $th) {
            Thana::firstOrCreate(['name' => $th['name']], $th);
        }
        $bograSadar = Thana::where('name', 'Bogra Sadar')->first();
        $shibganj = Thana::where('name', 'Shibganj')->first();
        $kahaloo = Thana::where('name', 'Kahaloo')->first();

        // 4. Products
        $p1 = Product::firstOrCreate(
            ['name' => 'Classic White Tee'],
            [
                'link' => 'https://example.com/classic-white-tee',
                'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=300&auto=format&fit=crop&q=80',
                'category' => 'T-Shirt',
                'size' => 'M, L, XL',
                'price' => 450.00,
                'selling_price' => 380.00,
                'created_by' => $admin->id,
            ]
        );

        $p2 = Product::firstOrCreate(
            ['name' => 'Floral Summer Dress'],
            [
                'link' => 'https://example.com/floral-summer-dress',
                'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=300&auto=format&fit=crop&q=80',
                'category' => 'Dress',
                'size' => 'S, M',
                'price' => 1200.00,
                'selling_price' => 950.00,
                'created_by' => $admin->id,
            ]
        );

        $p3 = Product::firstOrCreate(
            ['name' => 'Slim Fit Chinos'],
            [
                'link' => 'https://example.com/slim-fit-chinos',
                'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=300&auto=format&fit=crop&q=80',
                'category' => 'Pants',
                'size' => '32, 34, 36',
                'price' => 800.00,
                'selling_price' => 650.00,
                'created_by' => $admin->id,
            ]
        );

        // 5. Customers
        $c1 = Customer::firstOrCreate(
            ['phone' => '01711-000001'],
            ['name' => 'Rahim Ahmed', 'created_by' => $admin->id]
        );

        $c2 = Customer::firstOrCreate(
            ['phone' => '01811-000002'],
            ['name' => 'Sumaiya Khatun', 'created_by' => $admin->id]
        );

        $c3 = Customer::firstOrCreate(
            ['phone' => '01911-000003'],
            ['name' => 'Milon Islam', 'created_by' => $admin->id]
        );

        // 6. Orders
        $ord1 = Order::firstOrCreate(
            ['order_no' => 'ORD-0001'],
            [
                'customer_id' => $c1->id,
                'order_date' => now()->subDays(5)->format('Y-m-d'),
                'exp_delv_date' => now()->subDays(2)->format('Y-m-d'),
                'address' => 'House 12, Road 3, Bogra Sadar',
                'thana_id' => $bograSadar?->id,
                'status' => 'delivered',
                'created_by' => $admin->id,
            ]
        );
        if ($ord1->wasRecentlyCreated) {
            OrderDetail::create([
                'order_id' => $ord1->id,
                'product_id' => $p1->id,
                'quantity' => 2,
                'making_price' => 300.00,
                'asking_price' => 400.00,
                'selling_price' => 380.00,
            ]);
        }

        $ord2 = Order::firstOrCreate(
            ['order_no' => 'ORD-0002'],
            [
                'customer_id' => $c2->id,
                'order_date' => now()->subDays(3)->format('Y-m-d'),
                'exp_delv_date' => now()->addDays(1)->format('Y-m-d'),
                'address' => 'Mokamtola Bazar, Shibganj',
                'thana_id' => $shibganj?->id,
                'status' => 'processing',
                'created_by' => $admin->id,
            ]
        );
        if ($ord2->wasRecentlyCreated) {
            OrderDetail::create([
                'order_id' => $ord2->id,
                'product_id' => $p2->id,
                'quantity' => 1,
                'making_price' => 700.00,
                'asking_price' => 1000.00,
                'selling_price' => 950.00,
            ]);
        }

        $ord3 = Order::firstOrCreate(
            ['order_no' => 'ORD-0003'],
            [
                'customer_id' => $c3->id,
                'order_date' => now()->subDays(1)->format('Y-m-d'),
                'exp_delv_date' => now()->addDays(3)->format('Y-m-d'),
                'address' => 'Station Road, Kahaloo',
                'thana_id' => $kahaloo?->id,
                'status' => 'shipped',
                'created_by' => $admin->id,
            ]
        );
        if ($ord3->wasRecentlyCreated) {
            OrderDetail::create([
                'order_id' => $ord3->id,
                'product_id' => $p3->id,
                'quantity' => 1,
                'making_price' => 500.00,
                'asking_price' => 700.00,
                'selling_price' => 650.00,
            ]);
        }
    }
}
