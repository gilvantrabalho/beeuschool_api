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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("teacher_id")->nullable();
            $table->unsignedBigInteger("student_id");
            $table->string("title");
            $table->text("last_message");
            $table->string("send_to", 1);
            $table->string("token");
            $table->timestamps();

            $table->foreign("teacher_id")->references("id")->on("teachers");
            $table->foreign("student_id")->references("id")->on("students");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
