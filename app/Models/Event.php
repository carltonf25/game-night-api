<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;
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
    'id', 'event_code', 'title', 'description', 'date', 'location', 'header_image, user_id'
  ];

  /**
   * Event relations 
   * 
   */
  public function guests()
  {
    return $this->belongsToMany('App\Models\Guest', 'events_guests');
  }

  public function comments()
  {
    return $this->hasMany('App\Models\Comment');
  }

  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }
}
