<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_money', function (Blueprint $table) {
            $table->id();
            $table->integer('from_currency');
            $table->integer('to_currency');
            $table->integer('user_id');
            $table->decimal('charge',20,10);
            $table->decimal('from_amount',20,10);
            $table->decimal('to_amount',20,10);
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
        Schema::dropIfExists('exchange_money');
    }
}
