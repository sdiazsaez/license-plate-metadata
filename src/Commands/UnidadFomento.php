<?php

namespace Larangular\LicensePlateMetadata\Commands;

use Illuminate\Console\Command;
use Larangular\LicensePlateMetadata\Http\Controllers\LicensePlateMetadata\LicensePlateMetadataController;

class LicensePlateMetadata extends Command {

    protected $signature = 'uf:load';
    protected $description = 'Scrape and load missing UF values from last record date.';
    private $UFController;

    public function __construct() {
        parent::__construct();
        $this->UFController = new LicensePlateMetadataController();
    }

    public function handle() {
        $this->UFController->loadFromLastRecord();
    }
}
