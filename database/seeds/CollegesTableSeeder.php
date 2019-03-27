<?php

use App\College;
use Illuminate\Database\Seeder;

class CollegesTableSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {

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
}
