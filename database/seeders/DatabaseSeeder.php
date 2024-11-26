<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(40)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            PermissionSeeder::class,
            DemoSeeder::class,
            PageSeeder::class,
            SidebarSeeder::class
        ]);

        $path = database_path('scripts/products.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);

        $path = database_path('scripts/inventory.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);

        $path = database_path('scripts/orders.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);

        $path = database_path('scripts/commands.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);

    }
}
