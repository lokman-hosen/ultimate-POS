<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductcatalogueSettingsToBusinessTable extends Migration
{
    public function up()
    {
        Schema::table('business', function (Blueprint $table) {
            if (!Schema::hasColumn('business', 'productcatalogue_settings')) {
                $table->text('productcatalogue_settings')->nullable()->after('common_settings');
            }
        });
    }

    public function down()
    {
        Schema::table('business', function (Blueprint $table) {
            if (Schema::hasColumn('business', 'productcatalogue_settings')) {
                $table->dropColumn('productcatalogue_settings');
            }
        });
    }
}