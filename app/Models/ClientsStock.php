<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientsStock extends Model
{
    protected $table = 'clients_stock';
    protected $fillable = ['client_id', 'branch_id', 'product_id', 'stock'];

    public function client()
  {
        return $this->belongsTo(Client::class, 'client_id');
  }

  public function branch()
  {
        return $this->belongsTo(Branch::class, 'branch_id');
  }

    public function product()
  {
    return $this->belongsTo(Product::class, 'product_id');
  }

}
