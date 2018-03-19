<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PlayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions = ['GK', 'DEF', 'MID', 'ATK'];

        for ($i = 0; $i < 750; $i++) {
            $faker = Faker::create();
            DB::table('players')->insert([
                [
                    'club_id' => rand(1, 50),
                    'first_name' => $faker->firstNameMale,
                    'last_name' => $faker->lastName,
                    'position' => $positions[rand(0, 3)],
                    'number' => $faker->randomNumber($nbDigits = 2, $strict = true),
                    'price' => $faker->numberBetween($min = 3000, $max = 10000),
                    'wont_play' => 0,
                    'created_at'    =>  date("Y-m-d H:i:s", time()),
                    'updated_at'    =>  date("Y-m-d H:i:s", time()),
                    'league_id' => 1,
                ]
            ]);
        }
    }
}
