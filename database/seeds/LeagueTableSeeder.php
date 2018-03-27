<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class LeagueTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for ($i = 0; $i < 10; $i++) {
        //     $faker = Faker::create();
        //     DB::table('leagues')->insert([
        //         [
        //             'name' => $faker->company,
        //             'number_of_rounds' => 30 
        //         ]
        //     ]);
        // }
        $faker = Faker::create();
        DB::table('leagues')->insert([
            [
                'name' => "Romerike",
                'number_of_rounds' => 26,
                'current_round' => 1,
            ]
        ]);
        DB::table('leagues')->insert([
            [
                'name' => "Innlandet",
                'number_of_rounds' => 22,
                'current_round' => 1,                
            ]
        ]);
    }
}
