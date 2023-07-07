<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKycFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kyc_forms', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('user_type');
            $table->integer('type')->comment('1 = input, 2 = file, 3 = textarea');
            $table->string('label');
            $table->string('name');
            $table->integer('required')->comment('1 = yes, 0 = no');
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
        Schema::dropIfExists('kyc_forms');
    }
}
