<?php

namespace Larangular\LicensePlateMetadata\Tests;

use Illuminate\Support\Collection;
use Larangular\LicensePlateMetadata\Http\Controllers\LicensePlateMetadata\Gateway;
use Larangular\LicensePlateMetadata\Http\Controllers\LicensePlateMetadata\LicensePlateMetadataController;
use Larangular\LicensePlateMetadata\Models\LicensePlateMetadata;

class LicensePlateMetadataTest extends AbstractTestCase {

    private $gateway;
    private $lpmController;

    public function testJsonSchema() {
        $this->get(config('license-plate-metadata.route_prefix'))
             ->assertJsonStructure([
                 'national_id',
                 'name',
                 'license_plate',
                 'type',
                 'make',
                 'model',
                 'year',
                 'color',
                 'engine_number',
                 'chassis',
             ]);
    }

    public function testGetLicensePlateMetadata() {
        $licensePlate = 'HCZJ85';
        $lp = $this->getLicensePlateMetadataController()->getLicensePlateMetadata($licensePlate);
        dd($lp);
        $date = date('Y-m-d');
        $this->assertTrue($this->isRequestDateEqual($date));
    }

    public function testRandomDateLicensePlateMetadata() {
        $date = $this->getRandomDate();
        $this->assertTrue($this->isRequestDateEqual($date));
    }

    public function testMissingValue() {
        $date = $this->getRandomDate(1733055681, 1933055681);
        $lastValue = LicensePlateMetadata::lastFirst()
                                         ->first();
        $response = $this->getGateway()
                         ->current($date);

        $responseDate = date_format(date_create($response->date), 'Y-m-d');

        $isDateEqual = ($responseDate == $date);
        $isEqualToLastValue = ($lastValue->UF == $response->UF);

        $this->assertTrue(($isDateEqual && $isEqualToLastValue));
    }


    private function getGateway(): Gateway {
        if (!isset($this->gateway)) {
            $this->gateway = new Gateway();
        }
        return $this->gateway;
    }

    private function getLicensePlateMetadataController(): LicensePlateMetadataController {
        if (!isset($this->lpmController)) {
            $this->lpmController = resolve(LicensePlateMetadataController::class);
        }
        return $this->lpmController;
    }

    private function getRandomDate(?int $min, ?int $max) {
        if (is_null($min)) {
            $min = 1262055681;
        }

        if (is_null($max)) {
            $max = 1533055681;
        }

        $int = mt_rand($min, $max);
        $date = date("Y-m-d", $int);
        return $date;
    }

    private function isRequestDateEqual($date): bool {
        $response = $this->getGateway()
                         ->current($date);
        $responseDate = date_format(date_create($response->date), 'Y-m-d');
        return ($responseDate == $date);
    }

}
