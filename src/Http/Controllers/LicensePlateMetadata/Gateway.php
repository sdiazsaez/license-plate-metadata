<?php

namespace Larangular\LicensePlateMetadata\Http\Controllers\LicensePlateMetadata;

use Larangular\LicensePlateMetadata\Models\LicensePlateMetadata;
use Larangular\LicensePlateMetadata\Http\Resources\LicensePlateMetadataResource;
use Larangular\RoutingController\{Controller,
    Contracts\HasPagination,
    Contracts\HasResource,
    Contracts\IGatewayModel,
    Contracts\RecursiveStoreable,
    RecursiveStore\RecursiveOption};


class Gateway extends Controller implements IGatewayModel {

    public function model() {
        return LicensePlateMetadata::class;
    }

    public function allowedMethods() {
        return [];
    }

    public function getLicensePlate(string $licensePlate) {
        $lpc = resolve(LicensePlateMetadataController::class);
        return $lpc->getLicensePlateMetadata($licensePlate);
    }

}
