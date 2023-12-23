<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
  const ORDER_STATUS_CHANGE = 'order_status_change';
  const DELIVERY_EXPECTED = 'delivery_expected';
  const LOW_STOCK = 'low_stock';
  const NEW_ORDER = 'new_order';


  protected $fillable = ['user_id', 'type', 'email', 'app'];


  public function user()
  {
      return $this->belongsTo(User::class);
  }
}
