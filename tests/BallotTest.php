<?php

namespace LBHurtado\Ballot\Tests;

use WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Database\QueryException;
use LBHurtado\Ballot\Models\{Ballot, Position, Candidate, BallotCandidate};

class BallotTest extends TestCase
{
	/** @test */
	public function model_has_code_attribute()
	{
        /*** arrange ***/
        $code = $this->faker->numberBetween(1000,9999) . '-' . $this->faker->numberBetween(10000,99990);

        /*** act ***/
		$ballot = Ballot::create($attributes = compact('code'));

        /*** assert ***/
		$this->assertEquals($attributes, Arr::only($ballot->toArray(), array_keys($attributes)));
	}

	/** @test */
	public function model_has_factory()
	{
        /*** arrange ***/
        $code = $this->faker->word;

        /*** act ***/	
        $ballot = factory(Ballot::class)->create(compact('code'));

        /*** assert ***/
        $this->assertEquals($code, $ballot->code);
	}

	/** @test */
	public function ballot_code_attribute_is_unique()
	{
        /*** arrange ***/
		$code = $this->faker->word;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
		Ballot::create(compact('code'));
		Ballot::create(compact('code'));
	}

	/** @test */
	public function ballot_has_pivot_with_position_candidate_models_and_qty()
	{
        /*** arrange ***/
        $ballot = factory(Ballot::class)->create();
        $position = factory(Position::class)->create();
		$candidate = factory(Candidate::class)->create();
		$votes = 1;

        /*** act ***/
		$pivot = BallotCandidate::conjure($position, $candidate, $votes);
		$ballot->addCandidate($position, $candidate, $pivot);

        /*** assert ***/
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'position_id' => $position->id,
			'candidate_id' => $candidate->id,
			'votes' => $votes
		]);
	}

	/** @test */
	public function ballot_candidate_pivot_qty_defaults_to_1()
	{
        /*** arrange ***/
        $ballot = factory(Ballot::class)->create();
        $position = factory(Position::class)->create();
		$candidate = factory(Candidate::class)->create();

        /*** act ***/
		$pivot = BallotCandidate::conjure($position, $candidate);
		$ballot->addCandidate($position, $candidate, $pivot);

        /*** assert ***/
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'candidate_id' => $candidate->id,
			'votes' => 1
		]);
	}

	/** @test */
	public function ballot__add_candidate_default_to_pivot()
	{
        /*** arrange ***/
        $ballot = factory(Ballot::class)->create();
        $position = factory(Position::class)->create();
		$candidate = factory(Candidate::class)->create();

        /*** act ***/
		$ballot->addCandidate($position, $candidate);

        /*** assert ***/
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'candidate_id' => $candidate->id,
			'votes' => 1
		]);
	}

	/** @test */
	public function ballot_candidate_pivot_is_unique()
	{
        /*** arrange ***/
        $ballot = factory(Ballot::class)->create();
        $position = factory(Position::class)->create();
		$candidate = factory(Candidate::class)->create();
		$votes = 1;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
		$pivot = BallotCandidate::conjure($position, $candidate, $votes);
		$ballot->addCandidate($position, $candidate, $pivot);
		$ballot->addCandidate($position, $candidate, $pivot);
	}

	/** @test */
	public function ballot_can_generate_all_positions()
	{

	}
}