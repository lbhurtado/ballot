<?php

namespace LBHurtado\Ballot\Tests;

use LBHurtado\Ballot\BallotServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
	use WithFaker;
	
    public function setUp(): void
    {
        parent::setUp();

        include_once __DIR__.'/../database/migrations/create_positions_table.php.stub';
        include_once __DIR__.'/../database/migrations/create_candidates_table.php.stub';
        include_once __DIR__.'/../database/migrations/create_ballots_table.php.stub';

        (new \CreatePositionsTable)->up();
        (new \CreateCandidatesTable)->up();
        (new \CreateBallotsTable)->up();

        $this->faker = $this->makeFaker('en_PH');
    }

    protected function getPackageProviders($app)
    {
        return [
            BallotServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
