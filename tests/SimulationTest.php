<?php

namespace LBHurtado\Ballot\Tests;

use LBHurtado\Ballot\Models\{Ballot, Candidate, Position, BallotCandidate};

class SimulationTest extends TestCase
{
	/** @array */
	protected $ballots_array = [
		[
			['President', 		'MACAPAGAL'],
			['Vice-President', 	'PELAEZ'],
			// ['Senator', 		'MANGLAPUS'],
			// ['Senator', 		'MANAHAN'],
			// ['Senator', 		'SUMULONG'],
			// ['Senator', 		'RODRIGO'],
			// ['Senator', 		'ANTONINO'],
			// ['Senator', 		'OSIAS'],
			// ['Senator', 		'KATIGBAK'],
			// ['Senator', 		'ROY'],
			// ['Senator', 		'ZIGA'],
			// ['Senator', 		'PAREDES'],
			// ['Senator', 		'GONZALES'],
			// ['Senator', 		'CLIMACO'],
			// ['Senator', 		'ALONTO'],
			// ['Senator', 		'ROSALES'],
		],
	];

	protected $ballots;

	public function setUp(): void
	{
		parent::setUp();


	}

    // /** @test */
    public function start_to_finish()
    {
        /*** arrange ***/
    	$ballot = factory(Ballot::class)->create();
    	// echo "\n";
    	$i = 0;
    	foreach ($this->ballots_array as $ballot_records) {
    		foreach ($ballot_records as $ballot_record) {

    			$candidate = Candidate::where('code', $ballot_record[1])->first();
    			// echo $candidate->code . "\n";
				$response = $this->json('POST', '/api/ballot/candidate', [
					'ballot_id' => $ballot->id,
					'candidate_id' => $candidate->id, 
		        ]);

				$response->assertStatus(200);

				// $this->assertDatabaseHas('ballot_candidate', [
				// 	'ballot_id' => $ballot->id,
				// 	'position_id' => $candidate->position->id, 
				// 	'candidate_id' => $candidate->id,
				// 	'votes' => 1,
				// ]);
    		}
    	}

    	$candidate = Candidate::where('code', 'MACAPAGAL')->first();
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'position_id' => $candidate->position->id, 
			'candidate_id' => $candidate->id,
			'votes' => 1,
		]);

  //   	$candidate = Candidate::where('code', 'PELAEZ')->first();
		// $this->assertDatabaseHas('ballot_candidate', [
		// 	'ballot_id' => $ballot->id,
		// 	'position_id' => $candidate->position->id, 
		// 	'candidate_id' => $candidate->id,
		// 	'votes' => 1,
		// ]);
    }
}