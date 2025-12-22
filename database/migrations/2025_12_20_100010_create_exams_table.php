<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->uuid('subject_public_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->uuid('created_by_public_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->uuid('class_public_id')->nullable();
            $table->unsignedBigInteger('school_year_id')->nullable();
            $table->uuid('school_year_public_id')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->json('settings')->nullable();
            $table->enum('status', ['draft','scheduled','running','closed'])->default('draft');
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            $table->foreign('subject_public_id')->references('public_id')->on('subjects')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('staff_members')->onDelete('set null');
            $table->foreign('created_by_public_id')->references('public_id')->on('staff_members')->onDelete('set null');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('set null');
            $table->foreign('class_public_id')->references('public_id')->on('classes')->onDelete('set null');
            $table->foreign('school_year_id')->references('id')->on('school_years')->onDelete('set null');
            $table->foreign('school_year_public_id')->references('public_id')->on('school_years')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exams');
    }
};
