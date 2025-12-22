<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique()->index('index_public_id');
            $table->unsignedBigInteger('subject_id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('department')->nullable();
            $table->timestamps();

            $table->unique(['name','code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('subjects');
    }
};
