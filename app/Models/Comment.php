<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model implements AuthenticatableContract, AuthorizableContract
{
  use Authenticatable, Authorizable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id', 'body', 'event_id', 'guest_id'
  ];

  /**
   * Comment relations 
   * 
   */
  public function events()
  {
    return $this->belongsTo('App\Models\Event');
  }

  public function guests()
  {
    return $this->belongsTo('App\Models\Guest');
  }
}
