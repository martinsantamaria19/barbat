<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('clients_stock', function (Blueprint $table) {
      $table->id();
      $table->foreignId('client_id')->constrained('clients');
      $table->foreignId('product_id')->constrained('products');
      $table->integer('stock');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('clients_stock');
  }
};
