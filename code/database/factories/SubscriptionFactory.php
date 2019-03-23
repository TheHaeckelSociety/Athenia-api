<?php
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Subscription\MembershipPlan::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'duration' => \App\Models\Subscription\MembershipPlan::DURATION_YEAR,
    ];
});
//
//$factory->define(\App\Models\Subscription\Subscription::class, function (Faker $faker) {
//    return [
//        'membership_plan_id' => factory(\App\Models\Subscription\MembershipPlan::class)->create()->id,
//    ];
//});