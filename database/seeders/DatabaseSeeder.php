<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Paing Soe Ko',
            'username' => 'mkt',
            'email' => 'admin@mkt.com',
            'password' => 'passw0rd'
        ]);

        $this->call(NRCSeeder::class);
    }
}
