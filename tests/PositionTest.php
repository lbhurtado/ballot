<?php

namespace LBHurtado\Ballot\Tests;

use Illuminate\Support\Arr;
use LBHurtado\Ballot\Models\Position;
use Illuminate\Database\QueryException;

class PositionTest extends TestCase
{
	/** @test */
	public function position_model_has_name_seats_and_level_attribute()
	{
        /*** arrange ***/
		$name = $this->faker->name;
		$level = $this->faker->numberBetween(1,4);
		$seats = $this->faker->numberBetween(1,12);

        /*** act ***/
		$position = Position::create($attributes = compact('name', 'seats', 'level'));

        /*** assert ***/
		$this->assertEquals($attributes, Arr::only($position->toArray(), array_keys($attributes)));
	}

	/** @test */
	public function position_model_name_attribute_is_unique()
	{
        /*** arrange ***/
		$name = $this->faker->name;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
		Position::create(compact('name'));
		Position::create(compact('name'));
	}

	/** @test */
	public function position_model_has_factory()
	{
        /*** arrange ***/
		$name = $this->faker->name;

        /*** act ***/	
        $position = factory(Position::class)->create(compact('name'));

        /*** assert ***/
        $this->assertEquals($name, $position->name);
	}

	/** @test */
	public function position_model_has_seeder()
	{
		$this->assertDatabaseHas('positions', ['name' => 'President']);
		$this->assertDatabaseHas('positions', ['name' => 'Vice-President']);
		$this->assertDatabaseHas('positions', ['name' => 'Senator']);
		// $this->assertDatabaseHas('positions', ['name' => 'Congressman']);
		// $this->assertDatabaseHas('positions', ['name' => 'Governor']);
		// $this->assertDatabaseHas('positions', ['name' => 'Vice-Governor']);
		// $this->assertDatabaseHas('positions', ['name' => 'Board Member']);
		// $this->assertDatabaseHas('positions', ['name' => 'Mayor']);
		// $this->assertDatabaseHas('positions', ['name' => 'Vice-Mayor']);
		// $this->assertDatabaseHas('positions', ['name' => 'Councilor']);
	}
}