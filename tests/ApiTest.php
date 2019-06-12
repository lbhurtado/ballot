<?php

namespace LBHurtado\Ballot\Tests;

use LBHurtado\Ballot\Models\{Ballot, Candidate};

class ApiTest extends TestCase
{
    /** @test */
    public function post_ballot_candidates()
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

    /** @test */
    public function read_ballot_candidates()
    {
        /*** arrange ***/
    	$ballot = factory(Ballot::class)->create();
    	$candidate = Candidate::all()->random();
    	$ballot_id = $ballot->id;
    	$candidate_id = null;

        /*** assert ***/
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'position_id' => $candidate->position->id, 
			'candidate_id' => null,
			'votes' => null,
		]);

        /*** act ***/
		$response = $this->json('GET', '/api/ballot/candidate', [
			'ballot_id' => $ballot->id,
        ]);

        /*** assert ***/
		$response
			->assertStatus(200)
			;
		
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'position_id' => $candidate->position->id, 
			'candidate_id' => null,
			'votes' => null,
		]);
    }

    /** @test */
    public function update_then_read_ballot_candidates_ultimately_the_same()
    {
        /*** arrange ***/
    	$ballot = factory(Ballot::class)->create();
    	$candidate = Candidate::all()->random();
    	$ballot_id = $ballot->id;
    	$candidate_id = null;

        /*** act ***/
		$updateResponse = $this->json('POST', '/api/ballot/candidate', [
			'ballot_id' => $ballot->id,
			'candidate_id' => $candidate->id, 
        ]);

		$readResponse = $this->json('GET', '/api/ballot/candidate', [
			'ballot_id' => $ballot->id,
        ]);

        /*** assert ***/
        $this->assertEquals($updateResponse->getData(), $readResponse->getData());
    }
}