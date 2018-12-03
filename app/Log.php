<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model {

  /**
   * @var array
   */
  protected $fillable = [
    'action', 'ip_address'
  ];

  /**
   * @param array $attributes
   */
  public function __construct(array $attributes = []) {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $this->ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $this->ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $this->ip_address = $_SERVER['REMOTE_ADDR'];
    }

    $this->username = \Auth::user()->username;
    parent::__construct($attributes);
  }
}
