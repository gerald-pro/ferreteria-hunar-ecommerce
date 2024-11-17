<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.env') !== 'local') {
            abort(403, 'Cannot run demo seeder in production!');
        }

        if (!User::where('email', 'admin@example.org')->count()) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.org',
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
            ]);
            $user->syncRoles('gerente comercial');
        }

        if (!User::where('email', 'cliente@example.org')->count()) {
            $user = User::create([
                'name' => 'Cliente',
                'email' => 'cliente@example.org',
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
            ]);
            $user->syncRoles('cliente');
        }

        if (!User::where('email', 'redactor@example.org')->count()) {
            $user = User::create([
                'name' => 'Redactor',
                'email' => 'redactor@example.org',
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
            ]);

            $user->syncRoles('cliente');
        }

        if (!User::where('email', 'editor@example.org')->count()) {
            $user = User::create([
                'name' => 'Editor',
                'email' => 'editor@example.org',
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
            ]);

            $user->syncRoles('cliente');
        }

        if (!User::where('email', 'guest@example.org')->count()) {
            $user = User::create([
                'name' => 'Guest',
                'email' => 'guest@example.org',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);

            $user->syncRoles('cliente');
        }

        if (!User::where('email', 'geraldjoseavalosseveriche@gmail.com')->count()) {
            $user = User::create([
                'name' => 'Gerald',
                'email' => 'geraldjoseavalosseveriche@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
            ]);

            $user->syncRoles('cliente');
        }
        
        User::factory(10)->create();
    }
}
