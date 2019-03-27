<?php

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
      $table->unsignedInteger('college_id')->nullable();
      $table->foreign('college_id')->references('id')->on('colleges');
      $table->string('username', 100)->unique();
      $table->string('password');
      $table->string('first_name');
      $table->string('middle_initial')->nullable();
      $table->string('last_name');
      $table->string('remember_token')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
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
