<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memes', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Meme category
            $table->enum("category", [
                "meme_generated",       // Generated for MemeDB
                "meme_created"          // Temp-created memes
            ]);

            // Meme type
            $table->enum("type", [
                "demotivational",
                "when",
                "blocks"
            ]);

            $table->boolean("is_public"); //->default("false");
            $table->text("filename");
            $table->text("owner_id");

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
        Schema::dropIfExists('memes');
    }
}
