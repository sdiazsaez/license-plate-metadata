<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2019-01-06
 */

namespace Larangular\LicensePlateMetadata\Facades;

use Illuminate\Support\Facades\Facade;

class LicensePlateMetadataController extends Facade {
    protected static function getFacadeAccessor() {
        return 'LicensePlateMetadataController';
    }
}
