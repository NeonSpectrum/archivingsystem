<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model {
  /**
   * @var array
   */
  protected $fillable = [
    'name', 'description', 'logo'
  ];
}
