<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMarkovChainTable
 * Text data-sets for memes
 */
class CreateMarkovChainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markov_chain', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("target")->unsigned();
            $table->bigInteger("next")->unsigned();
            $table->bigInteger("bank_id")->unsigned()->default(1);
            $table->timestamps();

            $table->foreign('target')->references('id')->on('wordbank')->onDelete('cascade');
            $table->foreign('next')->references('id')->on('wordbank')->onDelete('cascade');
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
        Schema::dropIfExists('markov_chain');
    }
}
