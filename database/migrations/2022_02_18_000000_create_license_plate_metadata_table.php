<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Larangular\MigrationPackage\Migration\Schematics;
use Larangular\Installable\Facades\InstallableConfig;

class CreateLicensePlateMetadataTable extends Migration {
    use Schematics;
    protected $name;
    private   $installableConfig;

    public function __construct() {
        $this->installableConfig = InstallableConfig::config('Larangular\LicensePlateMetadata\LicensePlateMetadataServiceProvider');
        $this->connection = $this->installableConfig->getConnection('license_plate_metadata');
        $this->name = $this->installableConfig->getName('license_plate_metadata');
    }

    public function up() {
        $this->create(function (Blueprint $table) {
            $table->string('license_plate')->unique();
            $table->string('license_plate_dv');
            $table->string('national_id');
            $table->string('name');
            $table->string('type');
            $table->string('make');
            $table->string('model');
            $table->string('year');
            $table->string('color');
            $table->string('engine_number');
            $table->string('chassis');

            if ($this->installableConfig->getTimestamp('license_plate_metadata')) {
                $table->timestamps();
            }
        });
    }

    public function down() {
        $this->drop();
    }
}

