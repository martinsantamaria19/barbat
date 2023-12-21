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
      Schema::create('branches', function (Blueprint $table) {
        $table->id();
        $table->string('branch_name');
        $table->foreignId('branch_client')->constrained('users');
        $table->string('branch_phone');
        $table->string('branch_email');
        $table->string('branch_address');
        $table->string('branch_city');
        $table->string('branch_state');
        $table->string('branch_rut');
        $table->enum('branch_status', ['active', 'inactive']);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
