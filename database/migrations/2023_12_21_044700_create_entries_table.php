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
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->dateTime('entry');
            $table->unsignedBigInteger('user');
            $table->unsignedBigInteger('patient');
            $table->unsignedBigInteger('origin');
            $table->string('type', 10);

            $table->foreign('user')->references('id')->on('users');
            $table->foreign('patient')->references('id')->on('patients');
            $table->foreign('origin')->references('id')->on('origins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
