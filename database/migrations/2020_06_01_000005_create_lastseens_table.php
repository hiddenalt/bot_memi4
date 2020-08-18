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

                "wordbank",
                "picbank",

                "wordbank_props",
                "picbank_props",

                "wordbank_props_new",
                "picbank_props_new",

                "wordbank_props_accepted",
                "picbank_props_accepted",

                "wordbank_props_denied",
                "picbank_props_denied"
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
