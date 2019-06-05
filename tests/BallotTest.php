<?php

namespace LBHurtado\Ballot\Tests;

use WithFaker;
use Illuminate\Support\Arr;
use LBHurtado\Ballot\Models\Ballot;
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
	public function model_code_attribute_is_unique()
	{
        /*** arrange ***/
		$code = $this->faker->word;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
		Ballot::create(compact('code'));
		Ballot::create(compact('code'));
	}
}