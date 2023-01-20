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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('month_reference');
            $table->date('due_date');
            $table->integer('day_payment');
            $table->enum('status', ['Em Aberto','Pago','Enviado','Atrasado', 'A Vencer', 'Recusado'])->default('Em Aberto');
            $table->string('file', 255)->nullable();
            $table->date('sent_in')->nullable();
            $table->string('transaction_key', 255)->nullable();
            $table->text('observation')->nullable();
            $table->date('paid_in')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
};
