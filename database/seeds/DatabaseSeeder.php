<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('LeagueTableSeeder');
        $this->call('ClubsTableSeeder');
        $this->call('PlayersTableSeeder');   
        $this->call('RolesTableSeeder');     
    }
}
