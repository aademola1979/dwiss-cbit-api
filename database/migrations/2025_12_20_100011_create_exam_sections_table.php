<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('exam_sections', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->unsignedBigInteger('exam_id');
            $table->string('title')->nullable();
            $table->integer('time_limit_minutes')->nullable();
            $table->boolean('shuffle')->default(false);
            $table->integer('ordering')->default(1);
            $table->timestamps();

            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_sections');
    }
};
