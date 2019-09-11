<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsGuestsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('events_guests', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->integer('event_id');
      $table->integer('guest_id');
      $table->timestamps();
    });
  }

  protected $attributes = [
    'bringing' => null
  ];

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('events_guests');
  }
}
