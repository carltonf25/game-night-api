<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // Create 10 users using the user factory;
    factory(App\Models\User::class, 10)->create();
  }
}
