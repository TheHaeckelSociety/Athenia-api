<?php
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Vote\Ballot::class, function (Faker $faker) {
    return [
        'type' => \App\Models\Vote\Ballot::TYPE_SINGLE_OPTION,
    ];
});
$factory->define(App\Models\Vote\BallotItem::class, function (Faker $faker) {
    return [
        'ballot_id' => factory(\App\Models\Vote\Ballot::class)->create()->id,
    ];
});
$factory->define(App\Models\Vote\BallotItemOption::class, function (Faker $faker) {
    return [
        'ballot_item_id' => factory(\App\Models\Vote\BallotItem::class)->create()->id,
        'subject_id' => factory(\App\Models\Wiki\Iteration::class)->create()->id,
        'subject_type' => 'iteration',
    ];
});
$factory->define(App\Models\Vote\BallotCompletion::class, function (Faker $faker) {
    return [
        'ballot_id' => factory(\App\Models\Vote\Ballot::class)->create()->id,
        'user_id' => factory(\App\Models\User\User::class)->create()->id,
    ];
});
$factory->define(App\Models\Vote\Vote::class, function (Faker $faker) {
    return [
        'ballot_completion_id' => factory(\App\Models\Vote\BallotCompletion::class)->create()->id,
        'ballot_item_option_id' => factory(\App\Models\Vote\BallotItemOption::class)->create()->id,
    ];
});