<?php

namespace LBHurtado\Ballot;

use Illuminate\Support\ServiceProvider;

class BallotServiceProvider extends ServiceProvider
{
    const PACKAGE_BALLOT_CONFIG = __DIR__.'/../config/config.php';
    const PACKAGE_POSITIONS_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_positions_table.php.stub';
    const PACKAGE_CANDIDATES_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_candidates_table.php.stub';

    public function boot()
    {
        $this->publishConfigs();
        $this->publishMigrations();
    }

    public function register()
    {
        $this->registerConfigs();
        $this->registerFacades();
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
                    self::PACKAGE_CANDIDATES_TABLE_MIGRATION_STUB => database_path('migrations/'.date('Y_m_d_His', time()).'_create_candidates_table.php'),
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
}
