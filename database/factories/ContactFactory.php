<?php

use Faker\Generator as Faker;

$factory->define(App\Contact::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'phone'=>$faker->phoneNumber,
        'user_id'=>App\User::all()->random()->id
        
    ];
});
