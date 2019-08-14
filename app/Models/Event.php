<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Event extends Model implements AuthenticatableContract, AuthorizableContract
{
  use Authenticatable, Authorizable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id', 'event_code', 'title', 'description', 'date'
  ];

  /**
   * Event relations 
   * 
   */
  public function guests()
  {
    return $this->belongsToMany('App\Models\Guest', 'events_guests');
  }

  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }
}