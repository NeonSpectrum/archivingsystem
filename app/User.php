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
    'username', 'password', 'role_id', 'first_name', 'middle_initial', 'last_name'
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

  public function getNameAttribute() {
    $name[] = $this->first_name;

    if ($this->middle_initial) {
      $name[] = $this->middle_initial;
    }

    $name[] = $this->last_name;

    return join(' ', $name);
  }

  public function getRoleAttribute() {
    return Roles::find($this->role_id);
  }

  public function getIsSuperAdminAttribute() {
    return stripos(Roles::find($this->role_id)->name, 'super-admin') !== false;
  }

  public function getIsAdminAttribute() {
    return stripos(Roles::find($this->role_id)->name, 'admin') !== false;
  }

  public function getAdminRoleAttribute() {
    $college = $this->role->name . '-admin';
    return Roles::where('name', $college)->first();
  }

  public function getMemberRoleAttribute() {
    $college = str_replace('-admin', '', $this->role->name);
    return Roles::where('name', $college)->first();
  }
}
