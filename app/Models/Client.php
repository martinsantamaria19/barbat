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
    'client_status',
    'owner',
  ];

  public function branches()
  {
    return $this->hasMany(Branch::class, 'branch_client');
  }

  public function packages()
  {
    return $this->hasMany(Package::class);
  }

  public function users()
  {
    return $this->hasMany(User::class, 'company', 'id');
  }

  public function stock()
  {
    return $this->hasMany(ClientsStock::class, 'client_id', 'id');
  }

  public function owner()
  {
      return $this->belongsTo(Client::class, 'owner');
  }

}
