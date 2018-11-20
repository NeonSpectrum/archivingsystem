<?php

use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('users', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('role_id');
      $table->foreign('role_id')->references('id')->on('roles');
      $table->string('username', 100)->unique();
      $table->string('password');
      $table->string('first_name');
      $table->string('middle_initial')->nullable();
      $table->string('last_name');
      $table->string('remember_token')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });

    $data = [
      ['username' => 'rnd', 'password' => 'ueccssrnd', 'role_id' => 1, 'first_name' => 'rnd', 'middle_initial' => '', 'last_name' => 'rnd'],
      ['username' => 'cas-admin', 'password' => '1234', 'role_id' => 2, 'first_name' => 'cas', 'middle_initial' => '', 'last_name' => 'admin'],
      ['username' => 'cba-admin', 'password' => '1234', 'role_id' => 3, 'first_name' => 'cba', 'middle_initial' => '', 'last_name' => 'admin'],
      ['username' => 'ccss-admin', 'password' => '1234', 'role_id' => 4, 'first_name' => 'ccss', 'middle_initial' => '', 'last_name' => 'admin'],
      ['username' => 'cengg-admin', 'password' => '1234', 'role_id' => 5, 'first_name' => 'cengg', 'middle_initial' => '', 'last_name' => 'admin'],
      ['username' => 'dentistry-admin', 'password' => '1234', 'role_id' => 6, 'first_name' => 'dentistry', 'middle_initial' => '', 'last_name' => 'admin'],
      ['username' => 'educ-admin', 'password' => '1234', 'role_id' => 7, 'first_name' => 'educ', 'middle_initial' => '', 'last_name' => 'admin'],
      ['username' => 'cas', 'password' => '1234', 'role_id' => 8, 'first_name' => 'cas', 'middle_initial' => '', 'last_name' => ''],
      ['username' => 'cba', 'password' => '1234', 'role_id' => 9, 'first_name' => 'cba', 'middle_initial' => '', 'last_name' => ''],
      ['username' => 'ccss', 'password' => '1234', 'role_id' => 10, 'first_name' => 'ccss', 'middle_initial' => '', 'last_name' => ''],
      ['username' => 'cengg', 'password' => '1234', 'role_id' => 11, 'first_name' => 'cengg', 'middle_initial' => '', 'last_name' => ''],
      ['username' => 'dentistry', 'password' => '1234', 'role_id' => 12, 'first_name' => 'dentistry', 'middle_initial' => '', 'last_name' => ''],
      ['username' => 'educ', 'password' => '1234', 'role_id' => 13, 'first_name' => 'educ', 'middle_initial' => '', 'last_name' => '']
    ];

    foreach ($data as $datum) {
      User::create($datum);
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('users');
  }
}
