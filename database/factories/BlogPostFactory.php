<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BlogPost;
use Faker\Generator as Faker;

$factory->define(BlogPost::class, function (Faker $faker) {
    $title = $faker->sentence(rand(3, 8), true);
    $txt = $faker->realText(rand(1000, 4000));
    $isPublished = rand(1, 5) > 1;

    $createdAt = $faker->dateTimeBetween('-3 month', '-2 month');

    $data = [
        'category_id'    => rand(1,11),
        'user_id'        => (rand(1, 5) == 5) ? 1 : 2,
        'title'          => $title,
        'slug'           => str_slug($title),
        'excerpt'        => $faker->text(rand(40, 100)),
        'content_raw'    => $txt,
        'content_html'   => $txt,
        'is_published'   => $isPublished,
        'published_at'   => $isPublished ? $faker->dateTimeBetween('-2 month', '-1 days') : null,
        'created_at'     => $createdAt,
        'updated_at'     => $createdAt,
    ];

    return $data;
});
