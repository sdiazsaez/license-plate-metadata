<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2019-01-05
 */

namespace Larangular\LicensePlateMetadata\Tests;

use Larangular\LicensePlateMetadata\LicensePlateMetadataServiceProvider;
use Larangular\LPScraper\LPScraperServiceProvider;
use Orchestra\Testbench\TestCase;

class AbstractTestCase extends TestCase {

    protected function getEnvironmentSetUp($app) {
        $app['config']->set('lp-scrapper',
            require(__DIR__ . '/../vendor/larangular/lp-scraper/config/lp-scraper.php'));
        $app['config']->set('license-plate-metadata', require(__DIR__ . '/../config/license-plate-metadata.php'));

        $connectionName = config('license-plate-metadata.connection');
        $app['config']->set('database.default', $connectionName);
        $app['config']->set('database.connections.' . $connectionName, [
            'driver'   => 'sqlite',
            'database' => __DIR__ . '/db.sqlite',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app) {
        return [
            LPScraperServiceProvider::class,
            LicensePlateMetadataServiceProvider::class,
        ];
    }

}
