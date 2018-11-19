<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model {

  /**
   * @var array
   */
  protected $fillable = [
    'action'
  ];

  /**
   * @param array $attributes
   */
  public function __construct(array $attributes = []) {
    $this->username = \Auth::user()->username;

    parent::__construct($attributes);
  }
}
