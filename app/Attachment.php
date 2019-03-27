<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model {
  /**
   * @var array
   */
  protected $fillable = ['filename'];

  /**
   * @return mixed
   */
  public function data() {
    return $this->belongsTo("App\Data", 'data_id');
  }
}
