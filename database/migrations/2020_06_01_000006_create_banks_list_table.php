<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBanksListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text("title");
            $table->text("description");
            $table->bigInteger("conversation_id")->unsigned();
            $table->boolean("is_private")->default(1);
            $table->timestamps();

            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
        });

        // Make utf8mb4 for emoji support
        DB::unprepared('ALTER TABLE `banks_list` CONVERT TO CHARACTER SET utf8mb4');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks_list');
        DB::unprepared('ALTER TABLE `banks_list` CONVERT TO CHARACTER SET utf8');
    }
}
