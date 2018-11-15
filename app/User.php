<?php

namespace App;

use App\Roles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
  use Notifiable;
  use SoftDeletes;

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
    'username', 'password', 'role_id', 'first_name', 'last_name'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token'
  ];

  /**
   * @param $password
   */
  public function setPasswordAttribute($password) {
    $this->attributes['password'] = bcrypt($password);
  }

  public function getRoleAttribute() {
    return Roles::find(\Auth::user()->role_id)->name;
  }

  public function getLogoAttribute() {
    return Roles::find(\Auth::user()->role_id)->logo;
  }
}
