@extends('layouts.app')

@section('title','Home')


@section('content')
    <div class="grid grid-cols-4 relative">
        <div class="col-span-3 relative top-[100px] grid lg:grid-cols-5 px-5 gap-5">
            @foreach ($filmList as $film)
            <a href="film/{{$film->id}}">
                <div class=" bg-neutral-800 rounded-sm h-[300px] mb-2 overflow-hidden relative hover:border border-white hover:border-2" href="film/{{$film->id}}">
                    <img src={{$film->cover_image_url}} alt="cover" class="h-[300px] w-full absolute">
                    <div class="flex flex-col space-y-2 p-5 text-center items-center justify-center absolute opacity-0 h-[300px] w-full ease-in duration-100 hover:duration-150 hover:opacity-100 hover:bg-black hover:bg-opacity-75">
                        <div class="text-xl">{{$film->title}}</div>
                        <div class="text-sm">{{$film->director}}</div>
                        <div class="text-sm">({{$film->release_year}})</div>
                        <div>🪙{{$film->price}}</div>
                    </div>
                </div>
                <h2>{{$film->title}}</h2>
            </a>
            @endforeach
        </div>
        <div class="border border-white px-5 fixed top-[100px] right-0 h-[600px] w-[380px]">
            <div class="text-lime-400 text-md font-semibold">Recently Added</div>
            <div class="grid grid-rows-3 h-[573px] w-full">
                @foreach($last_added as $film)
                <div class="grid col-span-2 grid-cols-4 px-2">
                    <div class="flex items-center">
                        <img src={{$film->cover_image_url}} alt="cover" class="h-[120px] w-[95px] absolute">
                    </div>
                </div>
                <div class="col-span-2 col-start-3 flex flex-col ml-10 relative space-y-1 items-center justify-center">
                    <div class="text-sm">Title: {{$film->title}}</div>
                    <div class="text-sm">Director: {{$film->director}}</div>
                    <div class="text-sm">Release Year: {{$film->release_year}}</div>
                    <div class="text-sm">Price: 🪙{{$film->price}}</div>
                    
                </div>
                @endforeach
            </div>
        </div>
    </div>
   <div class="relative bg-black text-white mt-[120px] px-10 mb-[80px] w-[1100px]">
        {{$filmList->withQueryString()->links()}}
    </div>
@endsection

