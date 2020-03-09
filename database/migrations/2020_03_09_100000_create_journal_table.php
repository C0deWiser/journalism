<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->timestamps();
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->string('email')->nullable();
            $table->string('object_type');
            $table->integer('object_id')->unsigned();
            $table->string('event');
            $table->json('payload')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('journal');
    }
}
