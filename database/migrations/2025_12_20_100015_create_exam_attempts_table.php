<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('session_id')->nullable();
            $table->uuid('exam_public_id')->nullable();
            $table->uuid('session_public_id')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->uuid('student_public_id')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->enum('status', ['in_progress','submitted','auto_submitted','graded','disqualified'])->default('in_progress');
            $table->decimal('score', 8, 2)->nullable();
            $table->json('raw_result')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->json('device_info')->nullable();
            $table->timestamps();

            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('exam_sessions')->onDelete('set null');
            $table->foreign('exam_public_id')->references('public_id')->on('exams')->onDelete('cascade');
            $table->foreign('session_public_id')->references('public_id')->on('exam_sessions')->onDelete('set null');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('student_public_id')->references('public_id')->on('students')->onDelete('cascade');
            $table->index(['exam_id','student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_attempts');
    }
};
