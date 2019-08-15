<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // Create 10 users using the user factory;
    factory(App\Models\Event::class, 10)->create();
  }
}
