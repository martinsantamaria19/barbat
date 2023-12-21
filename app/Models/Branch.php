<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'branch_name',
        'branch_client',
        'branch_phone',
        'branch_email',
        'branch_address',
        'branch_city',
        'branch_state',
        'branch_rut',
        'branch_status',
    ];
    
    public function client()
    {
        return $this->belongsTo(Client::class, 'branch_client');
    }
}
