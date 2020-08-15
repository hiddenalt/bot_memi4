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

            // Категория мемов
            $table->enum("category", [
                "meme_generated",            // Сгенерированные ботом
                "meme_created"               // Созданные пользователями
            ]);

            // Тип мема
            $table->enum("type", [
                "demotivation",
                "when",
                "blocks"
            ]);

            $table->boolean("is_public"); //->default("false");
            $table->text("filename"); // Имя файла в соответствующем хранилище
            $table->text("owner_id"); // Владелец генерации/созданного мема

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
