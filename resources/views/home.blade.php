@extends('layouts.app')

@section('title','Home')


@section('content')
    <div class="grid grid-cols-4">
        <div class="col-span-3 relative top-[100px] grid lg:grid-cols-5 px-5 gap-5">
            @foreach ($filmList as $film)
            <a href="film/{{$film->id}}">
                <div class=" bg-neutral-800 rounded-sm h-[300px] mb-2 overflow-hidden relative hover:border border-white hover:border-2" href="film/{{$film->id}}">
                    <img src={{$film->cover_image_url}} alt="cover" class="h-[300px] w-full absolute">
                    <div class="flex flex-col space-y-2 items-center justify-center absolute opacity-0 h-[300px] w-full ease-in duration-100 hover:duration-150 hover:opacity-100 hover:bg-black hover:bg-opacity-75">
                        <div class="text-xl">{{$film->title}}</div>
                        <div>Director : {{$film->director}}</div>
                        <div>Release Year : {{$film->release_year}}</div>
                        <div>🪙 {{$film->price}}</div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
@endsection

