<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('role_users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->uuid('role_public_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->uuid('user_public_id')->nullable();
            $table->primary(['role_id', 'user_id']);
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_public_id')->references('public_id')->on('roles')->onDelete('cascade');
            $table->foreign('user_public_id')->references('public_id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_user');
    }
};
