<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('merchant_id');
            $table->string('currency_code');
            $table->decimal('amount',20,10);
            $table->string('details');
            $table->string('web_hook');
            $table->string('custom');
            $table->string('cancel_url');
            $table->string('success_url');
            $table->string('customer_email');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('merchant_payments');
    }
}
