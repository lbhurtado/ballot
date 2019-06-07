<?php

namespace LBHurtado\Ballot\Tests;

use Intervention\Image\Facades\Image;
use LBHurtado\Ballot\BallotServiceProvider;
use Intervention\Image\ImageServiceProvider;
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

        include_once __DIR__.'/../database/seeds/PositionSeeder.php';
        (new \PositionSeeder)->run();

        $this->faker = $this->makeFaker('en_PH');
    }

    protected function getPackageProviders($app)
    {
        return [
            ImageServiceProvider::class,
            BallotServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Image' => Image::class
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
        $app['config']->set('ballot.files.image.source', 'tests/storage/app/public/image.png');
        $app['config']->set('ballot.files.image.destination', 'tests/storage/app/image.png');
    }
}
