<?php

use Illuminate\Database\Seeder;
use Role;

class RolesTableSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {

    $data = [
      ['name' => 'super-admin', 'description' => 'Super Admin'],
      ['name' => 'college-admin', 'description' => 'College Admin'],
      ['name' => 'researcher', 'description' => 'Researcher'],
      ['name' => 'guest', 'description' => 'Guest']
    ];

    foreach ($data as $datum) {
      Role::create($datum);
    }
  }
}
