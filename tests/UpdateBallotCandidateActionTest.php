<?php

namespace LBHurtado\Ballot\Tests;

use Opis\Events\EventDispatcher;
use LBHurtado\Ballot\Models\{Ballot, Candidate};
use Joselfonseca\LaravelTactician\CommandBusInterface;
use LBHurtado\Ballot\Actions\UpdateBallotCandidateAction;
use LBHurtado\Ballot\Requests\UpdateBallotCandidateRequest as Request;

class UpdateBallotCandidateActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->bus = app(CommandBusInterface::class);
        $this->dispatcher = app(EventDispatcher::class);
    }

    /** @test */
    public function action_ultimately_updates_a_ballot_when_invoked()
    {
        /*** arrange ***/
    	$ballot = factory(Ballot::class)->create();
        $ballot_id = $ballot->id;
        $ballot_code = $ballot->code;
    	$candidate1 = Candidate::where('code', 'MACAPAGAL')->first();
    	$candidate_id = $candidate1->id;
        $candidate_code = $candidate1->code;
		$request = Request::create('/api/ballot/candidate', 'POST', $attributes = compact('ballot_code', 'candidate_code'));

        /*** assert ***/
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'position_id' => $candidate1->position->id, 
			'candidate_id' => null,
			'votes' => null,
		]);

        /*** act ***/
        $action = new UpdateBallotCandidateAction($this->bus, $this->dispatcher, $request);
        $response = $action->__invoke();

        /*** assert ***/
        $this->assertEquals(200, $response->getStatusCode());
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'position_id' => $candidate1->position->id, 
			'candidate_id' => $candidate1->id,
			'votes' => 1,
		]);

        /*** arrange ***/
        $candidate2 = Candidate::where('code', 'PELAEZ')->first();;
        $candidate_id = $candidate2->id;
        $candidate_code = $candidate2->code;
        $request = Request::create('/api/ballot/candidate', 'POST', $attributes = compact('ballot_code', 'candidate_code'));

        /*** assert ***/
        $this->assertDatabaseHas('ballot_candidate', [
            'ballot_id' => $ballot->id,
            'position_id' => $candidate2->position_id, 
            'candidate_id' => null,
            'votes' => null,
        ]);

        /*** act ***/
        $action = new UpdateBallotCandidateAction($this->bus, $this->dispatcher, $request);
        $response = $action->__invoke();

        /*** assert ***/
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas('ballot_candidate', [
            'ballot_id' => $ballot->id,
            'position_id' => $candidate1->position_id, 
            'candidate_id' => $candidate1->id,
            'votes' => 1,
        ]);
        $this->assertDatabaseHas('ballot_candidate', [
            'ballot_id' => $ballot->id,
            'position_id' => $candidate2->position_id, 
            'candidate_id' => $candidate2->id,
            'votes' => 1,
        ]);
    }	
}