<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'SKU',
        'name',
        'description',
        'image',
        'stock',
        'status',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class);
    }

    public function descontarStock($cantidad) {
      $this->stock = $this->stock - $cantidad;
      $this->save();
    }

}
