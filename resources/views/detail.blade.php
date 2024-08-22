@extends('layouts.app')
@section('title','Detail')


@section('content')
    <div class="grid grid-cols-4">
        <div class="col-span-3 relative top-[80px] px-5">
            @if ($purchase != [] && $purchase->isNotEmpty())
                <video class="h-[700px] w-full" controls>
                    <source src={{$film->video_url}} type="video/mp4">
                </video>
            @else
                <div class="w-full h-[700px] flex flex-col items-center justify-center space-y-3 text-white">
                    <x-bxs-lock class="h-[100px]"/>
                    <h2 class="text-2xl font-bold">This Video Is Locked</h2>
                    <button id="open-modal-btn" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-black rounded-lg group bg-gradient-to-br from-teal-300 to-lime-300 group-hover:from-teal-300 group-hover:to-lime-300 dark:text-white dark:hover:text-black focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-lime-800">
                        <span class="relative px-5 py-2.5 transition-all ease-in duration-75 dark:bg-black rounded-md group-hover:bg-opacity-0">
                        BUY
                        </span>
                    </button>

                    <div id="modal" class="fixed z-[110] hidden inset-0">
                        <div
                            class="flex items-center justify-center min-h-screen transition-opacity"
                        >
                            <!-- Modal Box -->
                            <div class="flex flex-col items-center justify-between bg-white py-7 px-5 text-black rounded h-[170px] w-[300px]">

                            <h3 class="text-3xl text-md font-semibold text-black" id="modal-title">
                                🪙 {{$film->price}}
                            </h3>

                            <div class="flex space-x-3">

                                <button id="close-modal-btn" type="button" class="bg-black text-white ease-in duration-200 hover:bg-red-700 hover:text-black w-[100px] h-[50px] font-semibold flex items-center justify-center">
                                    Cancel
                                </button>
                                <form method="POST" action="/buy/{{$film->id}}">
                                    @csrf
                                    <button id="buy-btn" type="submit" film-id={{ $film->id }} class="bg-black text-white ease-in duration-200 hover:bg-lime-300 hover:text-black w-[100px] h-[50px] font-semibold flex items-center justify-center">
                                        Buy
                                    </button>
                                </form>    
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class=" px-5 relative top-[80px] right-[30px] w-[360px]">
            <img src={{$film->cover_image_url}} alt="cover" class="h-[210px] w-[150px] relative top-[50px] border border-white border-2">
            <h2 class="text-4xl relative top-[80px]">{{$film->title}}</h2>
            <div class="flex flex-col space-y-2 relative top-[100px]">
                <div class="mb-[10px] text-justify text-xs"><p>{{$film->description}}</p></div>
                <div>Director : {{$film->director}}</div>
                <div>Release Year : {{$film->release_year}}</div>
                <div>Genre : {{$film->genres}}</div>
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
    <script>
        document.getElementById("open-modal-btn").addEventListener("click", function() {
            document.getElementById("modal").classList.remove("hidden");
        });

        document.getElementById("close-modal-btn").addEventListener("click", function() {
            document.getElementById("modal").classList.add("hidden");
        });

    </script>
@endsection