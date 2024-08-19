@extends('layouts.app')
@section('title','Detail')

@section('content')
    <div class="grid grid-cols-4">
        <div class="col-span-3 relative top-[80px] px-5">
            <video class="h-[700px] w-full" controls>
                <source src={{$film->video_url}} type="video/mp4">
            </video>
        </div>
        <div class=" px-5 fixed top-[80px] right-[50px]">
            <img src={{$film->cover_image_url}} alt="cover" class="h-[210px] w-[150px] relative top-[50px] border border-white border-2">
            <h2 class="text-4xl relative top-[80px]">{{$film->title}}</h2>
            <div class="flex flex-col space-y-2 relative top-[100px]">
                <div class="mb-[10px]">{{$film->description}}</div>
                <div>Director : {{$film->director}}</div>
                <div>Release Year : {{$film->release_year}}</div>
                <div>Genre : {{$film->genre}}</div>
                <div>Price : 🪙 {{$film->price}}</div>
                @php
                    $duration = $film->duration ;
                    $hours = floor($duration / 3600);
                    $minutes = floor(($duration % 3600) / 60);
                    $seconds = $duration % 60;
                @endphp
                <div>Duration : {{$hours}} hours {{$minutes}} minutes {{$seconds}} seconds</div>

            </div>
        </div>
    </div>
@endsection