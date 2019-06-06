<?php

namespace LBHurtado\Ballot\Tests;

use WithFaker;
use Illuminate\Support\Arr;
use LBHurtado\Ballot\Models\{Ballot, Candidate, BallotCandidate};
use Illuminate\Database\QueryException;

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
	public function ballot_has_pivot_with_candidate_model_and_qty()
	{
        /*** arrange ***/
        $ballot = Ballot::create(['code' => $this->faker->word]);
		// $candidate = Candidate::create(['code' => $this->faker->word, 'name' => $this->faker->name]);
		$candidate = factory(Candidate::class)->create();
		$votes = 1;

        /*** act ***/
		$pivot = BallotCandidate::conjure($candidate, $votes);
		$ballot->addCandidate($candidate, $pivot);

        /*** assert ***/
		$this->assertDatabaseHas('ballot_candidate', [
			'ballot_id' => $ballot->id,
			'candidate_id' => $candidate->id,
			'votes' => $votes
		]);
	}

	/** @test */
	public function ballot_candidate_pivot_qty_defaults_to_1()
	{
        /*** arrange ***/
        $ballot = Ballot::create(['code' => $this->faker->word]);
		$candidate = Candidate::create(['code' => $this->faker->word, 'name' => $this->faker->name]);

        /*** act ***/
		$pivot = BallotCandidate::conjure($candidate);
		$ballot->addCandidate($candidate, $pivot);

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
        $ballot = Ballot::create(['code' => $this->faker->word]);
		$candidate = Candidate::create(['code' => $this->faker->word, 'name' => $this->faker->name]);

        /*** act ***/
		$ballot->addCandidate($candidate);

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
        $ballot = Ballot::create(['code' => $this->faker->word]);
		$candidate = Candidate::create(['code' => $this->faker->word, 'name' => $this->faker->name]);
		$votes = 1;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
		$pivot = BallotCandidate::conjure($candidate, $votes);
		$ballot->addCandidate($candidate, $pivot);
		$ballot->addCandidate($candidate, $pivot);
	}
}