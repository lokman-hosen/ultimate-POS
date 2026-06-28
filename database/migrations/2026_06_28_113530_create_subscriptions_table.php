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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('business_id');
            $table->foreign('business_id')
                ->references('id')
                ->on('business')
                ->onDelete('cascade');

            $table->unsignedInteger('package_id');
            $table->foreign('package_id')
                ->references('id')
                ->on('packages')
                ->onDelete('cascade');

            $table->decimal('package_price', 15, 2)->default(0);

            $table->json('package_details')->nullable();

            $table->date('start_date');
            $table->date('end_date');

            $table->date('trial_end_date')->nullable();

            $table->enum('status', [
                'waiting',
                'approved',
                'declined',
                'expired'
            ])->default('waiting');

            $table->string('paid_via')->nullable();
            $table->string('payment_transaction_id')->nullable();

            $table->unsignedInteger('created_id')->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
