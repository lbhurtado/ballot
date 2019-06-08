<?php

namespace LBHurtado\Ballot\Tests;

use WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Database\QueryException;
use LBHurtado\Ballot\Models\{Ballot, Position, Candidate, BallotCandidate};

class BallotCandidateTest extends TestCase
{
	/** @test */
	public function ballot_candidates_relations_has_pivot_with_position_and_votes_attributes()
	{
        /*** arrange ***/
        $ballot = factory(Ballot::class)->create();
        $position = factory(Position::class)->create();
		$candidate = factory(Candidate::class)->create();
		$votes = 1;

        /*** act ***/
		$pivot = (new BallotCandidate)
			->setPosition($position)
			->setVotes($votes)
			;

		$ballot->candidates()->attach($candidate, $pivot->getAttributes());

        /*** assert ***/
        $this->assertTrue($candidate->is($ballot->candidates->first()));
        $this->assertTrue($position->is($pivot->position));
        $this->assertEquals($votes, $pivot->votes);
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'position_id' => $position->id,
			'candidate_id' => $candidate->id,
			'votes' => $votes
		]);
	}

	/** @test */
	public function ballot_positions_relation_has_pivot_with_candidates_and_votes_attributes()
	{
        /*** arrange ***/
        $ballot = factory(Ballot::class)->create();
        $position = factory(Position::class)->create();
		$candidate = factory(Candidate::class)->create();
		$votes = 1;

        /*** act ***/
		$pivot = (new BallotCandidate)
			->setCandidate($candidate)
			->setVotes($votes)
			;

		$ballot->positions()->attach($position, $pivot->getAttributes());

        /*** assert ***/
        $this->assertTrue($position->is($ballot->positions->first()));
        $this->assertTrue($candidate->is($pivot->candidate));
        $this->assertEquals($votes, $pivot->votes);
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'position_id' => $position->id,
			'candidate_id' => $candidate->id,
			'votes' => $votes
		]);
	}

	/** @test */
	public function ballot_positions_can_be_auto_generated()
	{
        /*** arrange ***/
        $ballot = factory(Ballot::class)->create();
		Position::all()->each(function($position) use ($ballot) {

			/*** act ***/
			$ballot->positions()->attach($position, []);

			/*** assert ***/
			$this->assertDatabaseHas('ballot_candidate', [
				'ballot_id' => $ballot->id,
				'position_id' => $position->id, 
				'candidate_id' => null,
				'votes' => null,
			]);
		});
	}

	/** @test */
	public function ballot_positions_can_update_pivot_candidate_and_vote_after_being_generated()
	{
        /*** arrange ***/
        $ballot = factory(Ballot::class)->create();
        $position = Position::all()->random();
        $ballot->positions()->attach($position, []);

		$pivot = BallotCandidate::withPosition($position)->first();

        /*** assert ***/
       	$this->assertEquals(1, BallotCandidate::count());
        $this->assertEquals(1, $pivot->count());
		$this->assertTrue($position->is($pivot->position));
		$this->assertNull($pivot->candidate);
		$this->assertNull($pivot->votes);
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'position_id' => $position->id, 
			'candidate_id' => null,
			'votes' => null,
		]);

		/*** act ***/
		$pivot
			->setCandidate($candidate = factory(Candidate::class)->create())
			->setVotes($votes = 1)
			->save()
			;

        /*** assert ***/
        $this->assertEquals(1, BallotCandidate::count());
        $this->assertEquals(1, $pivot->count());
		$this->assertTrue($position->is($pivot->position));
		$this->assertTrue($candidate->is($pivot->candidate));
		$this->assertEquals(1, $pivot->votes);
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'position_id' => $position->id, 
			'candidate_id' => $candidate->id,
			'votes' => $votes,
		]);
	}

	/** @test */
	public function ballot_positions_pivot_defaults_to_1_vote()
	{
        /*** arrange ***/
        $ballot = factory(Ballot::class)->create();
        $candidate = Candidate::all()->random();
		$pivot = (new BallotCandidate)->setCandidate($candidate);

		/*** act ***/
        $ballot->positions()->attach($candidate->position, $pivot->getAttributes());

        /*** assert ***/        
        $this->assertEquals(1, $pivot->votes);
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'position_id' => $candidate->position->id, 
			'candidate_id' => $candidate->id,
			'votes' => 1,
		]);
	}

	/** @test */
	public function ballot_positions_pivot_vote_cannot_have_more_0_as_value()
	{
        /*** arrange ***/
        $ballot = factory(Ballot::class)->create();
        $candidate = Candidate::all()->random();
        $votes = 0;
        $pivot = (new BallotCandidate)->setCandidate($candidate)->setVotes($votes);

        /*** assert ***/ 
        $this->expectException(QueryException::class);

		/*** act ***/        
        $ballot->positions()->attach($candidate->position, $pivot->getAttributes());
	}

	/** @test */
	public function ballot_positions_pivot_vote_cannot_have_more_than_1_as_value()
	{
        /*** arrange ***/
        $ballot = factory(Ballot::class)->create();
        $candidate = Candidate::all()->random();
        $votes = $this->faker->numberBetween(2,1000);
        $pivot = (new BallotCandidate)->setCandidate($candidate)->setVotes($votes);

        /*** assert ***/ 
        $this->expectException(QueryException::class);

		/*** act ***/        
        $ballot->positions()->attach($candidate->position, $pivot->getAttributes());
	}
}