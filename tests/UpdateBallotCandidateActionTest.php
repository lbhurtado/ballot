<?php

namespace LBHurtado\Ballot\Tests;

use Opis\Events\EventDispatcher;
use LBHurtado\Ballot\Models\{Ballot, Candidate};
use Joselfonseca\LaravelTactician\CommandBusInterface;
use LBHurtado\Ballot\Actions\UpdateBallotCandidateAction;
use LBHurtado\Ballot\Requests\UpdateBallotCandidateRequest as Request;

class UpdateBallotCandidateActionTest extends TestCase
{
    /** @var string */
    protected $url = '/api/ballot/candidate';

    /** @var  CommandBusInterface */
    protected $bus;

    /** @var EventDispatcher */
    protected $dispatcher;

    /** @var Ballot */
    protected $ballot;

    public function setUp(): void
    {
        parent::setUp();

        $this->bus = app(CommandBusInterface::class);
        $this->dispatcher = app(EventDispatcher::class);
        $this->ballot = factory(Ballot::class)->create();
    }

    /** @test */
    public function action_ultimately_updates_a_ballot_when_invoked()
    {
        /*** arrange ***/
        $candidate1 = Candidate::where('code', 'MACAPAGAL')->first();

        /*** assert ***/
        $this->assertDatabaseHas('ballot_candidate', [
            'ballot_id' => $this->ballot->id,
            'position_id' => $candidate1->position->id,
            'candidate_id' => null,
            'seat_id' => 1,
            'votes' => null,
        ]);

        /*** act ***/
        $action = new UpdateBallotCandidateAction($this->bus, $this->dispatcher, Request::create($this->url, 'POST', [
            'ballot_code' => $this->ballot->code,
            'candidate_code' => $candidate1->code,
            'seat_id' => 1
        ]));
        $response = $action->__invoke();

        /*** assert ***/
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas('ballot_candidate', [
            'ballot_id' => $this->ballot->id,
            'position_id' => $candidate1->position->id,
            'candidate_id' => $candidate1->id,
            'seat_id' => 1,
            'votes' => 1,
        ]);
    }

    /** @test */
    public function action_ultimately_updates_a_ballot_when_invoked_2()
    {
        /*** arrange ***/
        $candidate2 = Candidate::where('code', 'PELAEZ')->first();
        $candidate3 = Candidate::where('code', 'OSMEÑA')->first();

        /*** assert ***/
        $this->assertTrue($candidate3->position->is($candidate2->position));
        $this->assertEquals(1, $candidate2->position->seats);
        $this->assertDatabaseHas('ballot_candidate', [
            'ballot_id' => $this->ballot->id,
            'position_id' => $candidate2->position_id,
            'candidate_id' => null,
            'seat_id' => 1,
            'votes' => null,
        ]);

        /*** act ***/
        $action = new UpdateBallotCandidateAction($this->bus, $this->dispatcher, Request::create($this->url, 'POST', [
            'ballot_code' => $this->ballot->code,
            'candidate_code' =>  $candidate2->code,
            'seat_id' => 1
        ]));
        $response = $action->__invoke();

        /*** assert ***/
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas('ballot_candidate', [
            'ballot_id' => $this->ballot->id,
            'position_id' => $candidate3->position_id,
            'candidate_id' => $candidate2->id,
            'seat_id' => 1,
            'votes' => 1,
        ]);
        $this->assertDatabaseMissing('ballot_candidate', [
            'ballot_id' => $this->ballot->id,
            'position_id' => $candidate3->position_id,
            'candidate_id' => $candidate3->id,
            'seat_id' => 1,
            'votes' => 1,
        ]);

        /*** act ***/
        $action = new UpdateBallotCandidateAction($this->bus, $this->dispatcher, Request::create($this->url, 'POST', [
            'ballot_code' => $this->ballot->code,
            'candidate_code' => $candidate3->code,
            'seat_id' => 1
        ]));
        $response = $action->__invoke();

        /*** assert ***/
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas('ballot_candidate', [
            'ballot_id' => $this->ballot->id,
            'position_id' => $candidate3->position_id,
            'candidate_id' => $candidate3->id,
            'seat_id' => 1,
            'votes' => 1,
        ]);
        $this->assertDatabaseMissing('ballot_candidate', [
            'ballot_id' => $this->ballot->id,
            'position_id' => $candidate3->position_id,
            'candidate_id' => $candidate2->id,
            'seat_id' => 1,
            'votes' => 1,
        ]);
    }
}
