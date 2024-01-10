<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'company_rut',
        'company_state',
        'company_city',
        'client_status'
        // No es necesario incluir 'created_at' y 'updated_at' aquí
    ];

    /**
     * Obtiene las sucursales asociadas al cliente.
     */
    public function branches()
    {
        return $this->hasMany(Branch::class, 'branch_client');
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }


}
