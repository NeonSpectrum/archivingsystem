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
    return "{$this->first_name} {$this->middle_initial} {$this->last_name}";
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

  public function getAdminRoleIdAttribute() {
    $college = $this->role->name . '-admin';
    return Roles::where('name', $college)->first()->id;
  }

  public function getMemberRoleIdAttribute() {
    $college = str_replace('-admin', '', $this->role->name);
    return Roles::where('name', $college)->first()->id;
  }
}
