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
    'password' => User::bcrypt('12345'),
    'avatar_url' => $faker->imageUrl(100)
  ];
});

$factory->define(App\Models\Event::class, function (Faker\Generator $faker) {
  return [
    'title' => $faker->text(15),
    'event_code' => $faker->unique()->numberBetween(1, 999999),
    'description' => $faker->text(500),
    'date' => $faker->date,
    'header_image' => $faker->imageUrl(640),
    'user_id' => $faker->numberBetween(1, 10)
  ];
});
