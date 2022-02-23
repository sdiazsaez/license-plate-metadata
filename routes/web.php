<?php

Route::group([
    'prefix' => config('license-plate-metadata.route_prefix'),
    'middleware' => 'api',
    'namespace' => 'Larangular\LicensePlateMetadata\Http\Controllers',
    'as' => 'larangular.api.license-plate-metadata.'
], function () {
    Route::get('/{licensePlate}', 'LicensePlateMetadata\Gateway@getLicensePlate');
});
