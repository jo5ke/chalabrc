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
        $romerike = [
            "Aurskog-Høland",
            "Blaker",
            "Domkirkeodden",
            "EidsvoldIF",
            "EidsvoldTurn",
            "Fet",
            "Funnefoss_Vormsund",
            "Hauerseter",
            "Kløfta",
            "Rælingen",
            "RaumnesÅrnes",
            "Skedsmo",
            "Sørumsand",
            "Strømmen2",
            "UllKisa"
        ];
        for ($i = 0; $i < count($romerike); $i++) {
            $faker = Faker::create();
            DB::table('clubs')->insert([
                [
                   // 'league_id' => $faker->numberBetween($min = 1, $max = 10),
                    'league_id' => 1,
                    'name' => $romerike[$i]
                ]
            ]);
        }
    }
}
