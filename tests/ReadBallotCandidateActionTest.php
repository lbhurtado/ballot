<?php

namespace LBHurtado\Ballot\Tests;

use Illuminate\Support\Arr;
use Opis\Events\EventDispatcher;
use Illuminate\Support\Facades\Request;
use LBHurtado\Ballot\Models\{Ballot, Candidate};
use Joselfonseca\LaravelTactician\CommandBusInterface;
use LBHurtado\Ballot\Actions\ReadBallotCandidateAction;

class ReadBallotCandidateActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->bus = app(CommandBusInterface::class);
        $this->dispatcher = app(EventDispatcher::class);
    }

    /** @test */
    public function action_ultimately_reads_a_ballot_when_invoked()
    {
        /*** arrange ***/
        factory(Ballot::class)->create(['code' => 'ABC-0001']);
        factory(Ballot::class)->create(['code' => 'ABC-0002']);

        $ballot = factory(Ballot::class)->create(['code' => 'ABC-0003']);
    	$candidate = Candidate::all()->random();
    	$ballot_id = $ballot->id;
        $ballot_code = $ballot->code;
    	$candidate_id = $candidate->id;
		$request = Request::create('/api/ballot/candidate', 'GET', $attributes = compact('ballot_code'));

        /*** assert ***/
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'position_id' => $candidate->position->id, 
			'candidate_id' => null,
			'votes' => null,
		]);

        /*** act */
        $action = new ReadBallotCandidateAction($this->bus, $this->dispatcher, $request);
        $response = $action->__invoke();
        
        /*** assert ***/
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas('ballot_candidate', [
            'ballot_id' => $ballot->id,
            'position_id' => $candidate->position->id, 
            'candidate_id' => null,
            'votes' => null,
        ]);

        $this->assertEquals($ballot_code, Arr::get(Arr::first($response->getData(true)), 'code'));
    }	
}