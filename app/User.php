<?php

namespace App;

use App\Role;
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
    'username', 'password', 'role_id', 'college_id', 'first_name', 'middle_initial', 'last_name'
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
   * @return mixed
   */
  public function college() {
    return $this->belongsTo("App\College");
  }

  /**
   * @return mixed
   */
  public function role() {
    return $this->belongsTo("App\Role");
  }

  /**
   * @return mixed
   */
  public function getIsAdminAttribute() {
    return $this->role->name == 'college-admin' || $this->role->name == 'super-admin';
  }

  /**
   * @return mixed
   */
  public function getIsSuperAdminAttribute() {
    return $this->role->name == 'super-admin';
  }

  /**
   * @return mixed
   */
  public function getIsGuestAttribute() {
    return $this->role->name == 'guest';
  }

  public function getNameAttribute() {
    $name[] = $this->first_name;

    if ($this->middle_initial) {
      $name[] = $this->middle_initial;
    }

    $name[] = $this->last_name;

    return join(' ', $name);
  }

  public function getSearchNameAttribute() {
    return trim($this->last_name . ', ' . $this->first_name . ' ' . $this->middle_initial);
  }
  /**
   * @param $password
   */
  public function setPasswordAttribute($password) {
    $this->attributes['password'] = bcrypt($password);
  }
}
