<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User\User::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'name' => $faker->name,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
    ];
});
$factory->define(\App\Models\User\Message::class, function (Faker $faker) {
    return [
        'subject' => 'Test Subject',
        'email' => $faker->email,
        'template' => 'test_template',
        'data' => [],
        'user_id' => factory(\App\Models\User\User::class)->create()->id,
    ];
});
$factory->define(\App\Models\User\PasswordToken::class, function (Faker $faker) {
    return [
        'token' => str_random(40),
        'user_id' => factory(\App\Models\User\User::class)->create()->id,
    ];
});
