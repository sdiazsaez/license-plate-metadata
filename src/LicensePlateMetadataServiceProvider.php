<?php

namespace Larangular\LicensePlateMetadata;

use Larangular\Installable\{Contracts\HasInstallable, Contracts\Installable, Installer\Installer};
use Larangular\Installable\Support\{InstallableServiceProvider as ServiceProvider, PublisableGroups, PublishableGroups};
use Larangular\LicensePlateMetadata\Commands\LicensePlateMetadata;
use Larangular\LPScraper\LPScraperServiceProvider;

class LicensePlateMetadataServiceProvider extends ServiceProvider implements HasInstallable {

    protected $defer = false;

    public function boot() {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->publishesType([
            __DIR__ . '/../config/license-plate-metadata.php' => config_path('license-plate-metadata.php'),
        ], PublishableGroups::Config);

        $this->loadMigrationsFrom([
            __DIR__ . '/database/migrations',
            database_path('migrations/license-plate-metadata'),
        ]);

        $this->commands(LicensePlateMetadata::class);
    }

    public function register() {
        $this->app->register(LPScraperServiceProvider::class);
        $this->mergeConfigFrom(__DIR__ . '/../config/license-plate-metadata.php', 'license-plate-scrapper');
        $this->app->singleton('LPController', function () {
            return new Http\Controllers\LicensePlateMetadata\LicensePlateMetadataController();
        });

        $this->declareMigrationGlobal();
        $this->declareMigrationLicensePlateMetadata();

    }

    public function provides() {
        return ['LPController'];
    }

    public function installer(): Installable {
        return new Installer(__CLASS__);
    }

    private function declareMigrationGlobal(): void {
        $this->declareMigration([
            'connection'   => 'mysql',
            'migrations'   => [
                'local_path' => base_path() . '/vendor/larangular/license-plate-metadata/database/migrations',
            ]
        ]);
    }

    private function declareMigrationLicensePlateMetadata() {
        $this->declareMigration([
            'name'      => 'license_plate_metadata',
            'timestamp' => true,
        ]);
    }
}
