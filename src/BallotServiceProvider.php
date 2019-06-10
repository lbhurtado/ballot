<?php

namespace LBHurtado\Ballot;

use Illuminate\Support\ServiceProvider;
use LBHurtado\Ballot\Console\BallotProcess;
use LBHurtado\Ballot\Models\{Candidate, Position, Ballot};
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

class BallotServiceProvider extends ServiceProvider
{
    const PACKAGE_BALLOT_CONFIG = __DIR__.'/../config/config.php';
    const PACKAGE_FACTORY_DIR = __DIR__ . '/../database/factories';
    const PACKAGE_POSITIONS_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_positions_table.php.stub';
    const PACKAGE_CANDIDATES_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_candidates_table.php.stub';
    const PACKAGE_BALLOTS_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_ballots_table.php.stub';
    public function boot()
    {
        $this->publishConfigs();
        $this->publishMigrations();
        $this->mapFactories();

        if ($this->app->runningInConsole()) {
            $this->commands([
                BallotProcess::class,
            ]);
        }
    }

    public function register()
    {
        $this->registerConfigs();
        $this->registerFacades();
        $this->registerModels();
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

    protected function publishConfigs()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::PACKAGE_BALLOT_CONFIG => config_path('ballot.php'),
            ], 'ballot-config');
        }
    }

    public function mapFactories()
    {
        $this->app->make(EloquentFactory::class)->load(self::PACKAGE_FACTORY_DIR);
    }

    protected function registerConfigs()
    {
        $this->mergeConfigFrom(self::PACKAGE_BALLOT_CONFIG, 'ballot');
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
