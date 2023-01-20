<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_audio_corrections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('students_audio_id');
            $table->string('grade', '2');
            $table->text('description');
            $table->timestamps();

            $table->foreign('students_audio_id')->references('id')->on('students_audio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students_audio_corrections');
    }
};
