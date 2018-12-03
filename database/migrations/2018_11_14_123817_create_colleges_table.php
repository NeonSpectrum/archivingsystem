<?php

use App\College;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollegesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('colleges', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->string('description');
      $table->string('logo');
      $table->string('background');
      $table->timestamps();
      $table->softDeletes();
    });

    $data = [
      ['name' => 'cas', 'description' => 'College of Arts and Sciences', 'logo' => 'cas.png', 'background' => 'CAS.jpg'],
      ['name' => 'cba', 'description' => 'College of Business Administration', 'logo' => 'cba.png', 'background' => 'CBA.jpg'],
      ['name' => 'ccss', 'description' => 'College of Computer Studies and Systems', 'logo' => 'ccss.png', 'background' => 'CCSS.jpg'],
      ['name' => 'cengg', 'description' => 'College of Engineering', 'logo' => 'eng.png', 'background' => 'CENGG.jpg'],
      ['name' => 'dentistry', 'description' => 'College of Dentistry', 'logo' => 'dent.png', 'background' => 'Dentistry.jpg'],
      ['name' => 'educ', 'description' => 'College of Education', 'logo' => 'educ.png', 'background' => 'Educ.jpg']
    ];

    foreach ($data as $datum) {
      College::create($datum);
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('colleges');
  }
}
