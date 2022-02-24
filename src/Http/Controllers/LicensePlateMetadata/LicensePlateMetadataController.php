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
        $q = LicensePlateMetadata::byLicensePlate($licensePlate);
        $response = $q->first();

        if (!$response) {
            $scrapedLP = $this->scraper->getLicensePlateMetadata($licensePlate);

            if (!$scrapedLP || empty($scrapedLP)) {
                $this->tryEmailNotification($licensePlate);
            } else {
                LicensePlateMetadata::create($scrapedLP);
                $response = $q->first();
            }
        }

        return $response;
    }

    private function tryEmailNotification($content) {
        $email = config('license-plate-metadata.on_value_fail.email_to_notify');
        if (!is_null($email) && !empty($email)) {
            $message = [
                'HTTP_HOST' => @$_SERVER['HTTP_HOST'],
                'CONTENT'   => $content,
            ];

            mail($email, 'LPScraper fail', json_encode($message));
        }
    }

}
