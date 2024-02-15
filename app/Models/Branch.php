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

  public function clientsStock()
  {
    return $this->hasMany(ClientsStock::class, 'branch_id');
  }

  public function packages()
  {
    return $this->hasMany(Package::class, 'branch_id');
  }

  public function activities()
  {
    return $this->hasMany(Activity::class, 'branch_id');
  }
}
