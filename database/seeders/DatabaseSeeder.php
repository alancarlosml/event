<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // Seeders básicos (sem dependências)
            UserSeeder::class,
            ConfigurationSeeder::class,
            OptionSeeder::class,
            StateSeeder::class,
            
            // Seeders que dependem de States
            CitySeeder::class,
            
            // Seeders que dependem de Categories
            CategorySeeder::class,
            AreaSeeder::class,
            
            // Seeders independentes
            OwnerSeeder::class,
            
            // Seeders que dependem de Cities
            PlaceSeeder::class,
            
            // Seeders que dependem de Events, Areas, Owners, Places
            EventSeeder::class,
            
            // Seeders que dependem de Events
            LoteSeeder::class,
            CouponSeeder::class,
            
            // Seeders independentes
            ParticipanteSeeder::class,
        ]);
    }
}
