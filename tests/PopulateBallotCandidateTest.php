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
        $ballot = factory(Ballot::class)->withoutEvents()->create();

        /*** assert ***/
        $this->assertEquals(0, BallotCandidate::all()->count());

        /*** act ***/
        $job = (new PopulateBallotCandidate($ballot))->handle();
        $job = (new PopulateBallotCandidate($ballot))->handle();

        /*** assert ***/
        $this->assertEquals($positionCount, BallotCandidate::all()->count());
        $this->assertEquals($positionCount, BallotCandidate::withBallot($ballot)->count());

        /*** act ***/
        (new PopulateBallotCandidate($ballot = factory(Ballot::class)->create()))->handle();

        /*** assert ***/
        $this->assertEquals($positionCount*2, BallotCandidate::all()->count());
        $this->assertEquals($positionCount, BallotCandidate::withBallot($ballot)->count());
    }
}