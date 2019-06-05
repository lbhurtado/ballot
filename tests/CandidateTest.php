<?php

namespace LBHurtado\Ballot\Tests;

use WithFaker;
use Illuminate\Support\Arr;
use LBHurtado\Ballot\Models\Candidate;
use Illuminate\Database\QueryException;

class CandidateTest extends TestCase
{
	/** @test */
	public function model_has_code_and_name_attribute()
	{
        /*** arrange ***/
        $code = $this->faker->word;
		$name = $this->faker->name;

        /*** act ***/
		$candidate = Candidate::create($attributes = compact('code', 'name'));

        /*** assert ***/
		$this->assertEquals($attributes, Arr::only($candidate->toArray(), array_keys($attributes)));
	}

	/** @test */
	public function model_code_attribute_is_unique()
	{
        /*** arrange ***/
		$code = $this->faker->word;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
		Candidate::create(compact('code'));
		Candidate::create(compact('code'));
	}

	/** @test */
	public function model_name_attribute_is_unique()
	{
        /*** arrange ***/
		$name = $this->faker->name;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
		Candidate::create(compact('name'));
		Candidate::create(compact('name'));
	}
}