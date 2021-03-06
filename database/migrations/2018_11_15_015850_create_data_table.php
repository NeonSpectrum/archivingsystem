<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('data', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('college_id');
      $table->foreign('college_id')->references('id')->on('colleges');
      $table->string('title')->unique();
      $table->text('authors')->nullable();
      $table->text('keywords')->nullable();
      $table->string('category')->nullable();
      $table->string('publisher')->nullable();
      $table->string('proceeding_date')->nullable();
      $table->string('note')->nullable();
      $table->string('conference_name')->nullable();
      $table->string('url')->nullable();
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
    Schema::dropIfExists('data');
  }
}
