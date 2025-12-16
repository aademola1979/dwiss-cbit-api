<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable(true);
            $table->string('last_name');
            $table->integer('admission_number')->unique();
            $table->string('pin');
            $table->date('DOB');
            $table->date('admission_date');
            $table->enum('grade_admitted_to', ['JSS_1', 'JSS_2', 'JSS_3', 'SSS_1', 'SSS_2', 'SSS_3'])->default('JSS_1');
            $table->timestamp('deleted_at');
            $table->boolean('active');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
