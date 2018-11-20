<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Data extends Model {
  use SoftDeletes;

  /**
   * @var array
   */
  protected $dates = ['deleted_at'];

  /**
   * @var array
   */
  protected $fillable = [
    'role_id',
    'title',
    'authors',
    'keywords',
    'category',
    'publisher',
    'proceeding_date',
    'presentation_date',
    'publication_date',
    'note',
    'pdf_file_name',
    'certificate_file_name'
  ];
}
