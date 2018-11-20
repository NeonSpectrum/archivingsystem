<?php

use App\Roles;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('roles', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->string('description');
      $table->string('logo');
      $table->timestamps();
    });

    $data = [
      ['name' => 'super-admin', 'description' => 'Admin', 'logo' => 'ccss.png'],
      ['name' => 'cas-admin', 'description' => 'College of Arts and Sciences - Admin', 'logo' => 'cas.png'],
      ['name' => 'cba-admin', 'description' => 'College of Business Administration - Admin', 'logo' => 'cba.png'],
      ['name' => 'ccss-admin', 'description' => 'College of Computer Studies and Systems - Admin', 'logo' => 'ccss.png'],
      ['name' => 'cengg-admin', 'description' => 'College of Engineering - Admin', 'logo' => 'eng.png'],
      ['name' => 'dentistry-admin', 'description' => 'College of Dentistry - Admin', 'logo' => 'dent.png'],
      ['name' => 'educ-admin', 'description' => 'College of Education - Admin', 'logo' => 'educ.png'],
      ['name' => 'cas', 'description' => 'College of Arts and Sciences', 'logo' => 'cas.png'],
      ['name' => 'cba', 'description' => 'College of Business Administration', 'logo' => 'cba.png'],
      ['name' => 'ccss', 'description' => 'College of Computer Studies and Systems', 'logo' => 'ccss.png'],
      ['name' => 'cengg', 'description' => 'College of Engineering', 'logo' => 'eng.png'],
      ['name' => 'dentistry', 'description' => 'College of Dentistry', 'logo' => 'dent.png'],
      ['name' => 'educ', 'description' => 'College of Education', 'logo' => 'educ.png']
    ];

    foreach ($data as $datum) {
      Roles::create($datum);
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('roles');
  }
}
