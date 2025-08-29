<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Categorie;
use App\Models\Commune;
use App\Models\Decision;
use App\Models\User;
use App\Models\Violant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // \App\Models\User::factory(10)->create();
        //run php artisan migrate:fresh --seed
        Agent::factory(22)->create(); //22 specify the number of rows
        Categorie::factory(20)->create();
        Commune::factory(10)->create();
        Violant::factory(18)->create();
        User::factory(30)->create();
        Decision::factory(30)->create(); // in this factory there is infraction factory as well because each decision is related to a infraction
        

    }
}
