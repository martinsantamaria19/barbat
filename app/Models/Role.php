<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Role extends Model
{
    protected $fillable = ['name'];

    // Relación uno a muchos con User
    public function users()
    {
        return $this->hasMany(User::class, 'role_id'); // Asegúrate de que 'role_id' sea el nombre de la columna clave foránea en la tabla de usuarios
    }
}
