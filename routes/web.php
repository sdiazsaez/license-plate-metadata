<?php

Route::group([
    'prefix' => config('license-plate-metadata.route_prefix'), //'api/uf',
    'middleware' => 'api',
    'namespace' => 'Larangular\LicensePlateMetadata\Http\Controllers',
    'as' => 'larangular.api.license-plate-metadata.'
], function () {
    //Route::resource('/', 'LicensePlateMetadata\Gateway');
    Route::get('/{licensePlate}', 'LicensePlateMetadata\Gateway@getLicensePlate');
});
