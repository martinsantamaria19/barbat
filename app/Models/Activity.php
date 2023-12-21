<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
      'package_id',
      'status',
      'updated_by',
  ];

    public function package()
  {
    return $this->belongsTo(Package::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'updated_by');
  }
}
