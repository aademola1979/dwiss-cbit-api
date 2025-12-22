<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->unsignedBigInteger('exam_id');
            $table->string('session_key')->unique();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->integer('max_participants')->nullable();
            $table->json('settings')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->uuid('created_by_public_id')->nullable();
            $table->timestamps();

            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('staff_members')->onDelete('set null');
            $table->foreign('created_by_public_id')->references('public_id')->on('staff_members')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_sessions');
    }
};
