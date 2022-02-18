<?php

namespace Larangular\LicensePlateMetadata\Commands;

use Illuminate\Console\Command;
use Larangular\LicensePlateMetadata\Http\Controllers\LicensePlateMetadata\LicensePlateMetadataController;

class LicensePlateMetadata extends Command {

    protected $signature   = 'lp:get {--licenseplate= : Valid chilean license plate }';
    protected $description = 'Scrape and load missing chilean automobile license plates.';
    private   $LPController;

    public function __construct() {
        parent::__construct();
        $this->LPController = new LicensePlateMetadataController();
    }

    public function handle() {
        $lp = $this->option('licenseplate');
        if (!empty($lp)) {
            $this->LPController->getLicensePlateMetadata($lp);
        }
    }
}
