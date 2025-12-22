<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->unsignedBigInteger('attempt_id');
            $table->unsignedBigInteger('question_id');
            $table->json('answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->decimal('marks_awarded', 6, 2)->nullable();
            $table->dateTime('answered_at')->nullable();
            $table->timestamps();

            $table->uuid('attempt_public_id')->nullable();
            $table->uuid('question_public_id')->nullable();

            $table->foreign('attempt_id')->references('id')->on('exam_attempts')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('attempt_public_id')->references('public_id')->on('exam_attempts')->onDelete('cascade');
            $table->foreign('question_public_id')->references('public_id')->on('questions')->onDelete('cascade');
            $table->index('attempt_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_answers');
    }
};
