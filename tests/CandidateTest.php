<?php

namespace LBHurtado\Ballot\Tests;

use WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Database\QueryException;
use LBHurtado\Ballot\Models\{Candidate, Position, Ballot};

class CandidateTest extends TestCase
{
	/** @test */
	public function candidate_model_has_code_name_and_sequence_attribute_and_position_relation()
	{
        /*** arrange ***/
        $code = $this->faker->word;
		$name = $this->faker->name;
		$sequence = $this->faker->numberBetween(1,12);

        /*** act ***/
		$position = factory(Position::class)->create();
		$candidate = Candidate::create($position, $attributes = compact('code', 'name', 'sequence'));

        /*** assert ***/
		$this->assertEquals($attributes, Arr::only($candidate->getAttributes(), array_keys($attributes)));
		$this->assertTrue($position->is($candidate->position));
	}

	/** @test */
	public function candidate_model_has_factory()
	{
        /*** arrange ***/
		$name = $this->faker->name;

        /*** act ***/	
        $candidate = factory(Candidate::class)->create(compact('name'));

        /*** assert ***/
        $this->assertEquals($name, $candidate->name);
	}

	/** @test */
	public function candidate_model_code_attribute_is_unique()
	{
        /*** arrange ***/
		$code = $this->faker->word;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
        factory(Candidate::class)->create(compact('code'));
        factory(Candidate::class)->create(compact('code'));
	}

	/** @test */
	public function candidate_model_name_attribute_is_unique()
	{
        /*** arrange ***/
		$name = $this->faker->name;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
        factory(Candidate::class)->create(compact('name'));
        factory(Candidate::class)->create(compact('name'));
	}

	/** @test */
	public function candidate_has_many_ballot_candidates()
	{
        /*** arrange ***/
		$candidate = Candidate::where('code', 'MACAPAGAL')->first();
		$ballot1 = factory(Ballot::class)->create();
		$ballot2 = factory(Ballot::class)->create();

        /*** act ***/
		$ballot1->updatePivot($candidate, 1);
		$ballot2->updatePivot($candidate, 1);

        /*** assert ***/
		$this->assertEquals(2, $candidate->votes->count());
	}
}