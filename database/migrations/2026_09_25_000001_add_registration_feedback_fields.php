<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Additive only: fields required by the registration form revision.
 *  - business_locations: contact person, address line 2 and INE codes (names stay in state/city)
 *  - business: legal representative of a company (SL)
 *  - business_activities: main activities typed under "Other", offered to later registrations
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('business_locations', function (Blueprint $table) {
            $table->string('contact_person')->nullable()->after('mobile');
            if (! Schema::hasColumn('business_locations', 'address_line_2')) {
                $table->string('address_line_2')->nullable()->after('landmark');
            }
            $table->string('community_code', 2)->nullable()->after('country')->comment('INE autonomous community code');
            $table->string('province_code', 2)->nullable()->after('community_code')->comment('INE province code');
            $table->string('municipality_code', 5)->nullable()->after('province_code')->comment('INE municipality code');
        });

        Schema::table('business', function (Blueprint $table) {
            $table->string('legal_rep_name')->nullable()->after('tax_number_2');
            $table->string('legal_rep_position')->nullable()->after('legal_rep_name');
        });

        Schema::create('business_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_activities');

        Schema::table('business', function (Blueprint $table) {
            $table->dropColumn(['legal_rep_name', 'legal_rep_position']);
        });

        Schema::table('business_locations', function (Blueprint $table) {
            $table->dropColumn(['contact_person', 'community_code', 'province_code', 'municipality_code', 'address_line_2']);
        });
    }
};
