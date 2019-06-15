<?php

namespace LBHurtado\Ballot\Tests;

use LBHurtado\Ballot\Models\{Ballot, Candidate, BallotCandidate};

class ApiTest extends TestCase
{
	/** @var \LBHurtado\Ballot\Models\Ballot */
	protected $ballot;

	/** @var \LBHurtado\Ballot\Models\Candidate */
	protected $candidate1;

	/** @var \LBHurtado\Ballot\Models\Candidate */
	protected $candidate2;

	/** @var \LBHurtado\Ballot\Models\Candidate */
	protected $candidate3;

	public function setUp(): void
	{
		parent::setUp();

    	$this->ballot = factory(Ballot::class)->create();
    	$this->candidate2 = Candidate::where('code', 'MACAPAGAL')->first();
    	$this->candidate1 = Candidate::where('code', 'PELAEZ')->first();
    	$this->candidate3 = Candidate::where('code', 'OSMEÑA')->first();
	}

    /** @test */
    public function post_ballot_candidates()
    {
        /*** assert ***/
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $this->ballot->id,
			'position_id' => $this->candidate1->position_id, 
			'candidate_id' => null,
			'votes' => null,
		]);

        /*** act ***/
		$response = $this->json('POST', '/api/ballot/candidate', [
			'ballot_code' => $this->ballot->code,
			'candidate_code' => $this->candidate1->code, 
        ]);

        /*** assert ***/
		$response->assertStatus(200);
		
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $this->ballot->id,
			'position_id' => $this->candidate1->position->id, 
			'candidate_id' => $this->candidate1->id,
			'votes' => 1,
		]);

        /*** act ***/
		$response = $this->json('POST', '/api/ballot/candidate', [
			'ballot_code' => $this->ballot->code,
			'candidate_code' => $this->candidate2->code, 
        ]);

		$response->assertStatus(200);

        // tap((new BallotCandidate)->setCandidate($this->candidate2), function ($pivot) {
        //     $this->ballot
        //         ->positions()
        //         ->updateExistingPivot(
        //             $this->candidate2->position->id, 
        //             $pivot->getAttributes()
        //         );
        // });

		// dd($this->ballot->positions()->get()->toArray());
		// dd($this->candidate2->id);
		// dd($this->ballot->positions()->where('candidate_id', $this->candidate2->id)->first());
		$this->assertDatabaseMissing('ballot_candidate', [
			'ballot_id' => $this->ballot->id,
			'position_id' => $this->candidate1->position->id, 
			'candidate_id' => $this->candidate3->id,
			'votes' => 1,
		]);

        /*** act ***/
		$response = $this->json('POST', '/api/ballot/candidate', [
			'ballot_code' => $this->ballot->code,
			'candidate_code' => $this->candidate3->code, 
        ]);

        /*** assert ***/
		// $response->assertStatus(200);
		// $this->assertDatabaseHas('ballot_candidate', [
		// 	'ballot_id' => $this->ballot->id,
		// 	'position_id' => $this->candidate1->position->id, 
		// 	'candidate_id' => $this->candidate3->id,
		// 	'votes' => 1,
		// ]);
    }

    /** @test */
    public function read_ballot_candidates_has_no_affect_on_database()
    {
        /*** assert ***/
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $this->ballot->id,
			'position_id' => $this->candidate1->position->id, 
			'candidate_id' => null,
			'votes' => null,
		]);

        /*** act ***/
		$response = $this->json('GET', '/api/ballot/candidate', [
			'ballot_code' => $this->ballot->code,
        ]);

        /*** assert ***/
		$response
			->assertStatus(200)
			;
		
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $this->ballot->id,
			'position_id' => $this->candidate1->position->id, 
			'candidate_id' => null,
			'votes' => null,
		]);
    }

    /** @test */
    public function update_then_read_ballot_candidates_ultimately_the_same()
    {
        /*** act ***/
		$updateResponse = $this->json('POST', '/api/ballot/candidate', [
			'ballot_code' => $this->ballot->code,
			'candidate_code' => $this->candidate1->code, 
        ]);

		$readResponse = $this->json('GET', '/api/ballot/candidate', [
			'ballot_code' => $this->ballot->code,
        ]);

        /*** assert ***/
        $this->assertEquals($updateResponse->getData(), $readResponse->getData());

        /*** act ***/
		$updateResponse = $this->json('POST', '/api/ballot/candidate', [
			'ballot_code' => $this->ballot->code,
			'candidate_code' => $this->candidate2->code, 
        ]);

		$readResponse = $this->json('GET', '/api/ballot/candidate', [
			'ballot_code' => $this->ballot->code,
        ]);

        /*** assert ***/
        $this->assertEquals($updateResponse->getData(), $readResponse->getData());
    }
}