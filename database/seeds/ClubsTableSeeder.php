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
            "Aurskog",
            "Blaker",
            "Domkirkeodden",
            "Eidskog",
            "EidsvoldIF",
            "EidsvoldTurn",
            "Engerdal",
            "Faaberg11",
            "Fet",
            "Flisa",
            "Follebu",
            "Funnefoss Vormsund'",
            "Furnes",
            "Gran",
            "Hauerseter",
            "Kløfta",
            "Kolbu",
            "Lillehammer",
            "Moelven",
            "Nordreland",
            "Rælingen",
            "RaumnesÅrnes",
            "Redalen",
            "Ridabu",
            "Sander",
            "Skedsmo",
            "Sondreland",
            "Sørumsand",
            "Storhamar",
            "Strømmen2",
            "Toten",
            "Trysil",
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
DB::table('leagues')->insert([
    [
        'name' => $faker->company,
        'number_of_rounds' => 30
    ]
]);