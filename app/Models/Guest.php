<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Guest extends Model implements Authenticatable
{
  use AuthenticatableTrait;

  protected $fillable = ['id', 'name', 'user_id'];

  protected $attributes = [
    'user_id' => -1,
  ];

  public function events()
  {
    return $this->hasMany('App\Models\Event', 'events_guests');
  }

  public function comments()
  {
    return $this->hasMany('App\Models\Comment', 'guest_id', 'id');
  }
}
