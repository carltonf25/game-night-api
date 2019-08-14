<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Guest extends Model implements Authenticatable
{
  use AuthenticatableTrait;

  protected $fillable = ['id', 'name', 'user_id'];

  public function events()
  {
    return $this->belongsToMany('App\Models\Event', 'events_guests');
  }
}
