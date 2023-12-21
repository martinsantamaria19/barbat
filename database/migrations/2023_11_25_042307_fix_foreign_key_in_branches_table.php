<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixForeignKeyInBranchesTable extends Migration
{
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            // Asegúrate de que la tabla 'branches' y las columnas sean las correctas.
            // Eliminar la clave foránea actual
            $table->dropForeign(['branch_client']); // 'branch_client' es el nombre de la columna

            // Agregar la nueva clave foránea correcta
            $table->foreign('branch_client')->references('id')->on('clients');
        });
    }

    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {
            // En el método down, revierte los cambios hechos en up.
            $table->dropForeign(['branch_client']);

            // Aquí se asume que 'users' es la tabla y 'id' la columna correcta a la que se refería originalmente.
            $table->foreign('branch_client')->references('id')->on('users');
        });
    }
}
