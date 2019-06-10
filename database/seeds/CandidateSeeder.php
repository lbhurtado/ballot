<?php

use Illuminate\Database\Seeder;
use LBHurtado\Ballot\Models\{Candidate, Position};

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $position = Position::withName('President')->first();
        Candidate::create($position, ['code' => 'MARCOS', 'name' => 'Ferdinand Marcos Jr.']);
        Candidate::create($position, ['code' => 'ROBREDO', 'name' => 'Leni Robredo']);

        $position = Position::withName('Vice-President')->first();
        Candidate::create($position, ['code' => 'SOTTO', 'name' => 'Vicente Sotto III']);
        Candidate::create($position, ['code' => 'DUTERTE', 'name' => 'Sara Duterte']);
    }
}
