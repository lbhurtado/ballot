<?php

namespace LBHurtado\Ballot\Tests;

use WithFaker;
use Illuminate\Support\Arr;
use LBHurtado\Ballot\Models\Position;
use Illuminate\Database\QueryException;

class PositionTest extends TestCase
{
	/** @test */
	public function model_has_name_attribute()
	{
        /*** arrange ***/
		$name = $this->faker->name;

        /*** act ***/
		$position = Position::create($attributes = compact('name'));

        /*** assert ***/
		$this->assertEquals($attributes, Arr::only($position->toArray(), array_keys($attributes)));
	}

	/** @test */
	public function model_name_attribute_is_unique()
	{
        /*** arrange ***/
		$name = $this->faker->name;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
		Position::create(compact('name'));
		Position::create(compact('name'));
	}
}