<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
  /**
   * @var array
   */
  protected $fillable = [
    'name', 'description'
  ];

  /**
   * @return mixed
   */
  public function user() {
    return $this->hasOne('App\User');
  }
}
