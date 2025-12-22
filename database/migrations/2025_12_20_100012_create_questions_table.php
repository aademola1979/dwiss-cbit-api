<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->unsignedBigInteger('exam_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->enum('type', ['single_choice','multi_choice','true_false','short_text','long_text','file_upload'])->default('single_choice');
            $table->text('stem');
            $table->json('choices')->nullable();
            $table->json('answer')->nullable();
            $table->decimal('marks', 6, 2)->default(0);
            $table->decimal('negative_marks', 6, 2)->default(0);
            $table->json('meta')->nullable();
            $table->tinyInteger('difficulty')->nullable();
            $table->timestamps();

            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('exam_sections')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
