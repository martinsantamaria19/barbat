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
        Schema::create('receivers', function (Blueprint $table) {
          $table->id();
          $table->foreignId('package_id')->constrained('packages');
          $table->string('name');
          $table->string('lastname');
          $table->string('cedula');
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receivers');
    }
};
