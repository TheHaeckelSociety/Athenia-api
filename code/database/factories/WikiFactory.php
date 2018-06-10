<?php
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Models\Wiki\Article::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'created_by_id' => factory(\App\Models\User\User::class)->create()->id,
    ];
});