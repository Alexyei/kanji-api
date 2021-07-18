<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKanjisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kanji', function (Blueprint $table) {
            $table->id();
            $table->string('lang', 10);
            $table->string('word', 30);
            $table->string('translate');
            $table->timestamps();
        });

        Schema::create('kanji_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('kanji_id');
            $table->unsignedBigInteger('tag_id');
            $table->primary(['kanji_id','tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kanji');
        Schema::dropIfExists('kanji_tag');
    }
}
