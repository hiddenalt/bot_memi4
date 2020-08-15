<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWordbankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wordbank', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("bank_id")->unsigned()->default(1);
            $table->bigInteger("conversation_id")->unsigned();
            $table->mediumText("text");
            $table->enum("type", [
                "undefined",
                "noun",
                "adjective",
                "adverb",
                "verb",
                "phrase"
            ])->default("undefined");
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
        Schema::dropIfExists('wordbank');
    }
}
