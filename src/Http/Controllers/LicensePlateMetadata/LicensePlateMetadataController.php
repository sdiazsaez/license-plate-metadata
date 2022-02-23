<?php

namespace Larangular\LicensePlateMetadata\Http\Controllers\LicensePlateMetadata;

use Larangular\LicensePlateMetadata\Models\LicensePlateMetadata;
use Larangular\LPScraper\LicensePlateScraper as LPScraper;

class LicensePlateMetadataController {

    private $scraper;

    public function __construct() {
        $this->scraper = new LPScraper();
    }

    public function getLicensePlateMetadata(string $licensePlate): ?LicensePlateMetadata {
        $response = LicensePlateMetadata::byLicensePlate($licensePlate)
                                        ->first();

        if (!$response) {
            $scrapedLP = $this->scraper->getLicensePlateMetadata($licensePlate);

            if (!$scrapedLP || empty($scrapedLP)) {
                $this->tryEmailNotification($licensePlate);
            } else {
                $response = LicensePlateMetadata::create($scrapedLP);
            }
        }

        return $response;
    }

}
