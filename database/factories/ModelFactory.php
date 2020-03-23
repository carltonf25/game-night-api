<?php

use App\Models\User;
use App\Models\Event;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
  return [
    'username' => $faker->name,
    'email' => $faker->unique()->email,
    'password' => user::bcrypt('12345'),
    'avatar_url' => $faker->imageurl(100)
  ];
});

$factory->define(App\Models\Guest::class, function (Faker\Generator $faker) {
  return [
    'name' => $faker->name,
    'user_id' => -1
  ];
});

$factory->define(App\Models\Event::class, function (Faker\Generator $faker) {
  return [
    'title' => $faker->text(15),
    'event_code' => $faker->unique()->numberBetween(1, 999999),
    'description' => $faker->text(500),
    'date' => $faker->date(),
    'header_image' => $faker->imageUrl(640),
    'user_id' => 1,
    'time' => '2019-08-16 00:08:11',
    'location' => "Greg's place"
  ];
});
