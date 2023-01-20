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
        Schema::create('students', function (Blueprint $table) {
            $table->id()->autoIncrement(1000);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('plan_id');
            $table->integer('day_payment');
            $table->string('type')->default('A');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('teacher_id')->references('id')->on('teachers');
            $table->foreign('plan_id')->references('id')->on('plans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
