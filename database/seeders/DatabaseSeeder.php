<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Auth\Models\Role;
use App\Modules\Products\Models\Category;
use App\Modules\Products\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $adminRole = Role::create(['name' => 'Administrator', 'slug' =>
        'admin']);
        $userRole = Role::create(['name' => 'User', 'slug' => 'user']);

        // Users
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->roles()->attach($adminRole);

        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
        ]);
        $customer->roles()->attach($userRole);

        // Categories dan products
        $categories = Category::factory()->count(10)->create();
        $products = Product::factory()->count(50)->create();

        $products->each(function (Product $product) use ($categories) {
            $product->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->all(),
                ['added_at' => now()]
            );
        });
    }
}
