<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicbankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picbank', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("bank_id")->unsigned()->default(1);
            $table->bigInteger("conversation_id")->unsigned();
            $table->longText("path");
            $table->enum("status", [
                "pending",
                "denied",
                "accepted"
            ]);
            $table->timestamps();

            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            $table->foreign('bank_id')->references('id')->on('banks_list')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('picbank');
    }
}
