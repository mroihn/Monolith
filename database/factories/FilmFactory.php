<?php

namespace Database\Factories;

use Carbon\Carbon;
use Faker\Factory as faker;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\Factory;


class FilmFactory extends Factory
{
    // Schema::create('films', function (Blueprint $table) {
    //         $table->uuid('id')->primary();
    //         $table->string('title');
    //         $table->string('description');
    //         $table->string('director');
    //         $table->integer('release_year');
    //         $table->string('genres');
    //         $table->integer('price');
    //         $table->integer('duration');
    //         $table->string('video_url');
    //         $table->string('cover_image_url')->nullable();
    //         $table->timestamps();
    //     });
    public function definition(): array
    {
        $faker = faker::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));
        $years = range(2000, 2024);
        $price = range(50, 300);
        $timeString = $faker->runtime;

        list($hours, $minutes, $seconds) = explode(':', $timeString);
        $totalSeconds = $hours * 3600 + $minutes * 60 + $seconds;

        $file_path = "http://127.0.0.1:8000/uploads/";
        return [
            'title'=>$faker->movie,
            'description'=>$faker->overview,
            'director'=>$faker->name(),
            'release_year'=>Arr::random($years),
            'genres'=>implode(',', $faker->movieGenres(5)),
            'price'=>Arr::random($price),
            'duration'=>$totalSeconds,
            'video_url'=>"http://127.0.0.1:8000/uploads/video.mp4",
            'cover_image_url'=>"http://127.0.0.1:8000/uploads/cover.jpg",
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),

        ];
    }
}
