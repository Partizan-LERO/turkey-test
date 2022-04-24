<?php

namespace Database\Seeders;

use App\Domain\Team\Entity\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Team::factory()->make([
            'name' => 'Manchester City',
        ])->save();

        Team::factory()->make([
            'name' => 'Liverpool',
        ])->save();

        Team::factory()->make([
            'name' => 'Chelsea',
        ])->save();

        Team::factory()->make([
           'name' => 'Arsenal'
        ])->save();
    }
}
