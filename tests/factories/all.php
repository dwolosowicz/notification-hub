<?php

use App\Entity\Channel;
use App\Entity\User;
use League\FactoryMuffin\FactoryMuffin;
use League\FactoryMuffin\Faker\Facade as Faker;

/** @var $fm FactoryMuffin */

$fm->define(User::class)->setDefinitions([
    'username' => Faker::userName(),
    'usernameCanonical' => function($user) {
        return strtolower($user->getUsername());
    },
    'email' => Faker::safeEmail(),
    'emailCanonical' => function($user) {
        return strtolower($user->getEmail());
    },
    'password' => '$2y$13$NiwYiXVOu8Z5He5gDqoTJO.BawgTBRkqm0qbEu.wpkZokShKhTCBC' //password
]);

$fm->define(Channel::class)->setDefinitions([
    'name' => Faker::word(),
    'description' => Faker::sentence(),
    'user' => 'factory|User'
]);
