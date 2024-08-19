@extends('layouts.app')

@section('title','Home')
@section('nama',$user->username)
{{-- @if($user)
    @section('nama',$user->username)
@endif --}}

@section('content')
    <div class="grid grid-cols-4">
        <div class="col-span-3 relative top-[100px] grid lg:grid-cols-5 px-5 gap-5">
            @foreach ($filmList as $film)
            <a href="film/{{$film->id}}">
                <div class=" bg-neutral-800 rounded-sm h-[300px] mb-2 overflow-hidden text-white relative hover:border border-white hover:border-2" href="film/{{$film->id}}">
                    <img src={{$film->cover_image_url}} alt="cover" class="h-[210px] w-full">
                    <div class="px-2">
                        <span class="font-bold text-xl">{{$film->title}}</span>
                        <span class="block text-gray-500 text-sm">{{$film->release_year}}</span>
                        <span>{{$film->genre}}</span>
                    </div>
                    <div class="bg-black rounded-full p-2 absolute top-0 ml-2 mt-2 bg-opacity-75">
                        <span>🪙 {{$film->price}}</span>
                    </div>
                    
                </div>
            </a>
            @endforeach
        </div>
    </div>
@endsection

