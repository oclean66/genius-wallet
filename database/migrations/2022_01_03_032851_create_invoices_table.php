<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('invoice_to');
            $table->string('email');
            $table->string('address');
            $table->decimal('charge',20,10);
            $table->decimal('final_amount',20,10);
            $table->decimal('get_amount',10,10);
            $table->tinyInteger('pay_status')->comment('1 => paid, 0 => not paid');
            $table->tinyInteger('status')->comment('1 => published, 0 => not published , 2 => cancel');
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
        Schema::dropIfExists('invoices');
    }
}
