<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->string('name');
            $table->decimal('amount', 10, 2)->default(0);
            $table->unsignedBigInteger('type_id')->nullable();
            $table->uuid('type_public_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->uuid('class_public_id')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('recurring', ['none','monthly','termly','yearly'])->default('none');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('fee_types')->onDelete('set null');
            $table->foreign('type_public_id')->references('public_id')->on('fee_types')->onDelete('set null');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('set null');
            $table->foreign('class_public_id')->references('public_id')->on('classes')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('fees');
    }
};
