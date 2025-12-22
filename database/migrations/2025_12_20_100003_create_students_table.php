<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->unsignedBigInteger('user_id')->unique();
            $table->uuid('parent_id');
            $table->string('admission_no')->unique();
            $table->unsignedBigInteger('class_id');
            $table->string('section')->nullable();
            $table->date('dob');
            $table->enum('gender', ['male','female']);
            $table->enum('status', ['active','inactive','alumni','suspended', 'expelled'])->default('active');
            $table->json('detail')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('parent_id')->references('public_id')->on('parents')->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
};
