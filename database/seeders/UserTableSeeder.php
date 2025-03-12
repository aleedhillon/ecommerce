<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::doesntExist()) {
            User::factory()->create([
                'name' => 'Mr. Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
            ]);
            User::factory(2)->create();
        }
    }
}
