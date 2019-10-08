<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Need extends Model implements AuthenticatableContract, AuthorizableContract
{
  use Authenticatable, Authorizable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id', 'title' 
  ];

  /**
   * Need relations 
   * 
   */
  public function events()
  {
    return $this->belongsToMany('App\Models\Event');
  }

  public function guests()
  {
    return $this->belongsToMany('App\Models\Guest');
  }
}
