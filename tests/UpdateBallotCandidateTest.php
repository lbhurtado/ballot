<?php

namespace LBHurtado\Ballot\Tests;

use LBHurtado\Ballot\Jobs\UpdateBallotCandidate;
use LBHurtado\Ballot\Models\{BallotCandidate, Candidate, Ballot};

class UpdateBallotCandidateTest extends TestCase
{
    /** @test */
    public function job_updates_ballot_candidate_one_at_a_time()
    {
        /*** arrange ***/
        factory(Ballot::class)->create();
        $ballot = factory(Ballot::class)->create();
        $candidate = Candidate::all()->random();
        $pivot = BallotCandidate::where('ballot_id', $ballot->id)
            ->where('position_id', $candidate->position->id)
            ->first();

        /*** assert ***/
        $this->assertNotEquals(0, BallotCandidate::all()->count());
        $this->assertEquals(0, $pivot->votes);
        $this->assertEquals(0, $pivot->sum('votes'));

        /*** act ***/
        $job = (new UpdateBallotCandidate($ballot->id, $candidate->id))->handle();
        $pivot->refresh();

        /*** assert ***/
        $this->assertEquals(1, $pivot->votes);
        $this->assertEquals(1, $pivot->sum('votes'));
    }
}