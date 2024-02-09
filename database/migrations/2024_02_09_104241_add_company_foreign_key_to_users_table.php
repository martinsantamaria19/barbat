<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      // Cambiar el tipo de la columna 'company' a unsignedBigInteger
      $table->unsignedBigInteger('company')->nullable()->change();


      // Agregar la llave foránea
      $table
        ->foreign('company')
        ->references('id')
        ->on('clients')
        ->onDelete('set null');
    });
  }

  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      // Eliminar la llave foránea
      $table->dropForeign(['company']);
    });
  }
};
