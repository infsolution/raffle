<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

         \App\Models\User::factory()->create([
                'name' => 'Cicero',
                'email' => 'cicero@email.com',
                'phone' => '5586988698580',
                'password'=> bcrypt('12345678')
         ]);

         \App\Models\User::factory()->create([
            'name' => 'Marcelia',
            'email' => 'marcelia@email.com',
            'phone' => '5586988374653',
            'password'=> bcrypt('12345678')
     ]);

            \App\Models\User::factory()->create([
                'name' => 'Cliente Um',
                'email' => 'cliente1@email.com',
                'phone' => '5586981458963',
                'password'=> bcrypt('12345678')
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Cliente Dois',
            'email' => 'cliente2@email.com',
            'phone' => '5586994753623',
            'password'=> bcrypt('12345678')
        ]);
    }
}
