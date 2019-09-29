<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class User extends Model implements Authenticatable
{
  use AuthenticatableTrait;

  protected $fillable = ['username', 'email', 'password', 'api_token', 'avatar_url', 'plan_type'];

  protected $attributes = [
    'avatar_url' => 'https://i1.wp.com/www.mvhsoracle.com/wp-content/uploads/2018/08/default-avatar.jpg?ssl=1',
    'plan_type' => 'free'
  ];

  protected $hidden = ['password'];

  public function events()
  {
    return $this->hasMany('App\Models\Event', 'user_id');
  }
}
