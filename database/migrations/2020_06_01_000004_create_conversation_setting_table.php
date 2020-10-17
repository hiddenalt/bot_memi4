<?php

use App\Http\Controllers\BotSettings;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversation_setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("conversation_id")->unsigned();
            $table->enum('type', BotSettings::getSettingsTypes());
            $table->string("value")->default(0);
            $table->timestamps();

            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversation_setting');
        //TODO: foreign keys
//        $table->dropForeign('user_id'); $table->dropIndex('user_id'); $table->dropColumn('user_id')
    }
}
