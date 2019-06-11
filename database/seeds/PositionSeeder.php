<?php

use Illuminate\Database\Seeder;
use LBHurtado\Ballot\Models\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::create(['level' => 1, 'seats' => 1,  'name' => 'President']);
        Position::create(['level' => 1, 'seats' => 1,  'name' => 'Vice-President']);
        Position::create(['level' => 1, 'seats' => 12, 'name' => 'Senator']);
        // Position::create(['level' => 2, 'name' => 'Congressman']);
        // Position::create(['level' => 3, 'name' => 'Governor']);
        // Position::create(['level' => 3, 'name' => 'Vice-Governor']);
        // Position::create(['level' => 3, 'name' => 'Board Member']);
        // Position::create(['level' => 4, 'name' => 'Mayor']);
        // Position::create(['level' => 4, 'name' => 'Vice-Mayor']);
        // Position::create(['level' => 4, 'name' => 'Councilor']);
    }
}
