<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, HasProfilePhoto, HasTeams, Notifiable, TwoFactorAuthenticatable, HasRoles;

  protected $fillable = ['name', 'lastname', 'email', 'password', 'company', 'status', 'phone'];

  protected $hidden = ['password', 'remember_token', 'two_factor_recovery_codes', 'two_factor_secret'];

  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  protected $appends = ['profile_photo_url'];

  public function notificationSettings()
  {
    return $this->hasMany(NotificationSetting::class);
  }

  public function sendPasswordResetNotification($token)
  {
    $this->notify(new ResetPasswordNotification($token));
  }

  public function client()
  {
    return $this->belongsTo(Client::class, 'company', 'id');
  }
}
