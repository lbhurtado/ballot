<?php

namespace LBHurtado\Ballot\Tests;

use LBHurtado\Ballot\Models\{Ballot, Candidate};

class ApiTest extends TestCase
{
    /** @test */
    public function action_ultimately_updates_a_ballot_when_invoked_2_change_this()
    {

        /*** arrange ***/
    	$ballot = factory(Ballot::class)->create();
    	$candidate = Candidate::all()->random();
    	$ballot_id = $ballot->id;
    	$candidate_id = $candidate->id;

        /*** assert ***/
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'position_id' => $candidate->position->id, 
			'candidate_id' => null,
			'votes' => null,
		]);

        /*** act ***/
		$response = $this->json('POST', '/api/ballot/candidate', [
			'ballot_id' => $ballot->id,
			'candidate_id' => $candidate->id, 
        ]);

        /*** assert ***/
		$response
			->assertStatus(200)
			->assertJsonFragment([
				'votes' => null,
			])
			;
		
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'position_id' => $candidate->position->id, 
			'candidate_id' => $candidate->id,
			'votes' => 1,
		]);

		$this->assertDatabaseHas('ballot_candidate', [
			'candidate_id' => null,
			'votes' => null,
		]);

		// dd($response->getData());
    }
}