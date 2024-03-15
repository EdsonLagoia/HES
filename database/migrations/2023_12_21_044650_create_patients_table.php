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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('social_name', 80)->nullable();
            $table->string('mother', 80)->nullable();
            $table->date('birth_date')->nullable();
            $table->char('sus', 18)->nullable();
            $table->char('cpf', 14)->nullable();
            $table->char('phone', 15)->nullable();
            $table->string('street', 60)->nullable();
            $table->string('number', 10)->nullable();
            $table->string('district', 30)->nullable();
            $table->string('city', 80)->nullable();
            $table->char('state', 2)->nullable();
            $table->boolean('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
