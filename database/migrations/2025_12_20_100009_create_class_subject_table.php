<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('class_subject', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->uuid('class_public_id')->nullable();
            $table->unsignedBigInteger('subject_id');
            $table->uuid('subject_public_id')->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->uuid('teacher_public_id')->nullable();
            $table->timestamps();

            $table->unique(['class_id','subject_id']);
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('staff_members')->onDelete('set null');
            $table->foreign('class_public_id')->references('public_id')->on('classes')->onDelete('cascade');
            $table->foreign('subject_public_id')->references('public_id')->on('subjects')->onDelete('cascade');
            $table->foreign('teacher_public_id')->references('public_id')->on('staff_members')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_subject');
    }
};
