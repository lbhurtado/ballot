<?php

namespace LBHurtado\Ballot\Tests;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use LBHurtado\Ballot\BallotServiceProvider;
use Intervention\Image\ImageServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as BaseTestCase;
use LBHurtado\Tactician\TacticianServiceProvider;
use Joselfonseca\LaravelTactician\Providers\LaravelTacticianServiceProvider;


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

        include_once __DIR__.'/../database/seeds/CandidateSeeder.php';
        (new \CandidateSeeder)->run();

        $this->faker = $this->makeFaker('en_PH');

        $path = 'tests/storage/app/public';

        if(!File::isDirectory($path)){
            File::makeDirectory($path, 0777, true, true);
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            ImageServiceProvider::class,
            BallotServiceProvider::class,
            TacticianServiceProvider::class,
            LaravelTacticianServiceProvider::class,
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
        $app['config']->set('ballot.files.image.source', 'tests/storage/app/public/image.jpg');
        $app['config']->set('ballot.files.image.destination', 'tests/storage/app/public/image.jpg');
        $app['config']->set('ballot.files.image.qrcode', 'tests/storage/app/pulic/qrcode.jpg');
        $app['config']->set('ballot.files.temp', 'tests/storage/app/');
        $app['config']->set('ballot.qrcode.test', 'ABC-0003');
    }
}
