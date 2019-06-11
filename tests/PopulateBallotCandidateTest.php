<?php

namespace LBHurtado\Ballot\Tests;

use LBHurtado\Ballot\Jobs\PopulateBallotCandidate;
use LBHurtado\Ballot\Models\{BallotCandidate, Position, Ballot};

class PopulateBallotCandidateTest extends TestCase
{
	/** @test */
    public function job_persists_same_ballot_candidates_handled_multiple_times()
    {
        /*** arrange ***/
        $positionCount = Position::all()->count();
        $seatsCount = Position::all()->sum('seats');
        // dd(Position::all()->sum('seats'));
        $ballot = factory(Ballot::class)->withoutEvents()->create();

        /*** assert ***/
        $this->assertEquals(0, BallotCandidate::all()->count());

        /*** act ***/
        $job = (new PopulateBallotCandidate($ballot))->handle();
        $job = (new PopulateBallotCandidate($ballot))->handle();

        /*** assert ***/
        $this->assertEquals($seatsCount, BallotCandidate::all()->count());
        $this->assertEquals($seatsCount, BallotCandidate::withBallot($ballot)->count());

        /*** act ***/
        for ($i = 1; $i < $iteration = 2; $i++)
            (new PopulateBallotCandidate($ballot = factory(Ballot::class)->create()))->handle();

        /*** assert ***/
        $this->assertEquals($seatsCount*$iteration, BallotCandidate::all()->count());
        $this->assertEquals($seatsCount, BallotCandidate::withBallot($ballot)->count());
    }
}