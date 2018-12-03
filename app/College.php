<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class College extends Model {
  /**
   * @var array
   */
  protected $dates = ['deleted_at'];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'description', 'logo', 'background'
  ];

  /**
   * @return mixed
   */
  public function user() {
    return $this->hasMany('App\User');
  }

  /**
   * @return mixed
   */
  public function data() {
    return $this->hasMany("App\Data");
  }
}
