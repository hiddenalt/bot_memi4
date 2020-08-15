<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLastseensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lastseens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("conversation_id")->unsigned();
            $table->enum("type", [
                "self_reputation",
                "self_memes",
                "self_money",

                "wordbank", // Слов в словаре
                "picbank", // Картинок в словаре

                "wordbank_props", // Слов в предложке
                "picbank_props", // Картинок в предложке

                "wordbank_props_new", // Новых слов в предложке
                "picbank_props_new", // Новых картинок в преложке

                "wordbank_props_accepted", // Принятых слов в предложке
                "picbank_props_accepted", // Принятых слов в предложке

                "wordbank_props_denied", // Отклоненных слов в предложке
                "picbank_props_denied", // Отклоненных картинок в предложке
            ]);
            $table->string("value")->default(0);
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
        Schema::dropIfExists('lastseens');
    }
}
