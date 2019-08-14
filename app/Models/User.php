<?php

namespace App\Models;

use Illuminate\Database\Elqquent\Model;

use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class User extends Model implements Authenticatable
{
  use AuthenticatableTrait;

  protected $fillable = ['username', 'email', 'password', 'avatar_image', 'plan_type'];

  protected $hidden = ['password'];

  public function event()
  {
    return $this->hasMany('App\Models\Event');
  }
}
