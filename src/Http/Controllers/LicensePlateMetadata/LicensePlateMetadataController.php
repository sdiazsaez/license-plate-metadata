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

    public function loadRange($startDate, $endDate): void {
        $period = $this->datePeriodDaily($startDate, $endDate);
        foreach ($period as $value) {
            $this->current($value->format('Y-m-d'));
        }
    }

    public function loadFromLastRecord(): void {
        $lastRecord = LicensePlateMetadata::lastFirst()
                                          ->first();
        $today = date('Y-m-d');

        $this->loadRange($lastRecord->date, $today);
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

    private function datePeriodDaily($startDate, $endDate): \DatePeriod {
        return new \DatePeriod(new \DateTime($startDate), new \DateInterval('P1D'),
            (new \DateTime($endDate))->setTime(23, 59, 59));
    }

}
