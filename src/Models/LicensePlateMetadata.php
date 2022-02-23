<?php

namespace Larangular\LicensePlateMetadata\Models;

use Illuminate\Database\Eloquent\Model;
use Larangular\Installable\Facades\InstallableConfig;
use Larangular\Metadata\Traits\Metable;
use Larangular\RoutingController\Model as RoutingModel;

class LicensePlateMetadata extends Model {
    use RoutingModel, Metable;

    protected $fillable = [
        'id',
        'national_id',
        'name',
        'license_plate',
        'license_plate_dv',
        'type',
        'make',
        'model',
        'year',
        'color',
        'engine_number',
        'chassis',
    ];

    protected $with = [
        'meta',
    ];

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $installableConfig = InstallableConfig::config('Larangular\LicensePlateMetadata\LicensePlateMetadataServiceProvider');
        $this->connection = $installableConfig->getConnection('license_plate_metadata');
        $this->table = $installableConfig->getName('license_plate_metadata');
        $this->timestamps = $installableConfig->getTimestamp('license_plate_metadata');
    }

    public function scopeByLicensePlate($query, string $licensePlate) {
        return $query->where('license_plate', $licensePlate);
    }

    public function scopeByNationalId($query, string $nationalId) {
        return $query->where('national_id', $nationalId);
    }

}
