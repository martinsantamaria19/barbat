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
      Schema::create('activities', function (Blueprint $table) {
        $table->id();
        $table->foreignId('package_id')->constrained()->onDelete('cascade');
        $table->enum('status', ['processing', 'shipped', 'delivered']);
        $table->foreignId('updated_by')->constrained('users');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
