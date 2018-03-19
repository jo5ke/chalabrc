<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ClubsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 50; $i++) {
            $faker = Faker::create();
            DB::table('clubs')->insert([
                [
                    'league_id' => $faker->numberBetween($min = 1, $max = 10),
                    'name' => $faker->city
                ]
            ]);
        }
    }
}
