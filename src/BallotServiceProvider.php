<?php

namespace LBHurtado\Ballot;

use Illuminate\Support\ServiceProvider;
use LBHurtado\Ballot\Console\BallotProcess;
use LBHurtado\Ballot\Observers\BallotObserver;
use LBHurtado\Ballot\Models\{Candidate, Position, Ballot};
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use \Illuminate\Database\Eloquent\FactoryBuilder;

class BallotServiceProvider extends ServiceProvider
{
    const APPLICATION_POSITION_SEEDER = 'seeds/PositionSeeder.php';
    const APPLICATION_CANDIDATE_SEEDER = 'seeds/CandidateSeeder.php';
    const PACKAGE_ROUTE_API = __DIR__.'/../routes/api.php';
    const PACKAGE_BALLOT_CONFIG = __DIR__.'/../config/config.php';
    const PACKAGE_FACTORY_DIR = __DIR__ . '/../database/factories';
    const PACKAGE_POSITION_SEEDER = __DIR__.'/../database/seeds/PositionSeeder.php';
    const PACKAGE_CANDIDATE_SEEDER = __DIR__.'/../database/seeds/CandidateSeeder.php';
    const PACKAGE_TACTICIAN_FIELDS_CONFIG = __DIR__ . '/../config/tactician.fields.php';
    const PACKAGE_POSITIONS_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_positions_table.php.stub';
    const PACKAGE_CANDIDATES_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_candidates_table.php.stub';
    const PACKAGE_BALLOTS_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_ballots_table.php.stub';

    public function boot()
    {
        $this->observeModels();
        $this->publishConfigs();
        $this->publishMigrations();
        $this->publishSeeds();
        $this->publishCommands();
        $this->mapFactories();
        $this->publishMacros();
        $this->mapRoutes();
    }

    public function register()
    {
        $this->registerConfigs();
        $this->registerFacades();
        $this->registerModels();
    }

    protected function observeModels()
    {
        app('ballot.ballot')::observe(BallotObserver::class);
    }

    protected function publishMigrations()
    {
        if ($this->app->runningInConsole()) {
            if (! class_exists(CreatePositionsTable::class)) {
                $this->publishes([
                    self::PACKAGE_POSITIONS_TABLE_MIGRATION_STUB => database_path('migrations/'.date('Y_m_d_His', time()).'_create_positions_table.php'),
                ], 'ballot-migrations');
            }
        }
        if ($this->app->runningInConsole()) {
            if (! class_exists(CreateCandidatesTable::class)) {
                $this->publishes([
                    self::PACKAGE_CANDIDATES_TABLE_MIGRATION_STUB => database_path('migrations/'.date('Y_m_d_His', time()+50).'_create_candidates_table.php'),
                ], 'ballot-migrations');
            }
        }
        if ($this->app->runningInConsole()) {
            if (! class_exists(CreateBallotsTable::class)) {
                $this->publishes([
                    self::PACKAGE_BALLOTS_TABLE_MIGRATION_STUB => database_path('migrations/'.date('Y_m_d_His', time()+60).'_create_ballots_table.php'),
                ], 'ballot-migrations');
            }
        }
    }

    protected function publishSeeds()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::PACKAGE_POSITION_SEEDER => database_path(self::APPLICATION_POSITION_SEEDER),
                self::PACKAGE_CANDIDATE_SEEDER => database_path(self::APPLICATION_CANDIDATE_SEEDER),
            ], 'ballot-seeds');
        }
    }

    protected function publishConfigs()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::PACKAGE_BALLOT_CONFIG => config_path('ballot.php'),
            ], 'ballot-config');
        }
    }

    protected function publishCommands()
    {
        $this->commands([
            BallotProcess::class,
        ]);
    }

    public function mapFactories()
    {
        $this->app->make(EloquentFactory::class)->load(self::PACKAGE_FACTORY_DIR);
    }

    public function publishMacros()
    {
        FactoryBuilder::macro('withoutEvents', function () {
            $this->class::flushEventListeners();
          
            return $this;
        });
    }

    public function mapRoutes()
    {
        $this->loadRoutesFrom(self::PACKAGE_ROUTE_API);
    }

    protected function registerConfigs()
    {
        $this->mergeConfigFrom(self::PACKAGE_BALLOT_CONFIG, 'ballot');
        $this->mergeConfigFrom(self::PACKAGE_TACTICIAN_FIELDS_CONFIG, 'tactician.fields');
    }

    protected function registerFacades()
    {
        $this->app->singleton('ballot', function () {
            return new Ballot;
        });
    }

    protected function registerModels()
    {
        $this->app->singleton('ballot.candidate', function () {
            $class = config('ballot.classes.models.candidate', Candidate::class);
            return new $class;
        });
        $this->app->singleton('ballot.position', function () {
            $class = config('ballot.classes.models.position', Position::class);
            return new $class;
        });
        $this->app->singleton('ballot.ballot', function () {
            $class = config('ballot.classes.models.ballot', Ballot::class);
            return new $class;
        });
    }
}
