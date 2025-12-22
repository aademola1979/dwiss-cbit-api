<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->unsignedBigInteger('student_id');
            $table->uuid('student_public_id')->nullable();
            $table->unsignedBigInteger('fee_id');
            $table->uuid('fee_public_id')->nullable();
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->dateTime('paid_at')->nullable();
            $table->string('method')->nullable();
            $table->string('reference')->nullable();
            $table->enum('status', ['pending','completed','failed'])->default('pending');
            $table->string('receipt_url')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('student_public_id')->references('public_id')->on('students')->onDelete('cascade');
            $table->foreign('fee_id')->references('id')->on('fees')->onDelete('cascade');
            $table->foreign('fee_public_id')->references('public_id')->on('fees')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('fee_payments');
    }
};
