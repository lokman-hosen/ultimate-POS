<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_packages', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('business_id');
            $table->foreign('business_id')
                ->references('id')
                ->on('business')
                ->onDelete('cascade');

            $table->string('name');

            $table->enum('duration', [
                'monthly',
                'quarterly',
                'half-yearly',
                'yearly',
                'lifetime',
            ]);

            $table->decimal('amount', 22, 2)->default(0);

            // Stores JSON array of gym class IDs
            $table->json('classes')->nullable();

            $table->text('notes')->nullable();

            $table->boolean('enabled')->default(true);

            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('business_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gym_packages', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
            $table->dropForeign(['created_by']);
        });

        Schema::dropIfExists('gym_packages');
    }
};
