@extends('layouts.app')

@section('title','Login')

@section('content')
  <div class="flex flex-col items-center justify-center h-screen">
    <div class="bg-black text-white h-[300px] w-[500px] px-[20px] py-[20px] font-semibold rounded-lg bg-opacity-20">
        <h3 class="text-3xl mb-5">Login</h3>
        <form class="space-y-2" method="POST" action="{{ route('post_login') }}">
            @csrf
            <div>
              <input type="text" name="username" placeholder="Email or Username" class="bg-white border border-black
               text-black sm:text-sm rounded-lg block w-full p-2.5">
            </div>
            <div>
              <input type="password" name="password" id="password" placeholder="Type your password" class="bg-white border border-black
               text-black sm:text-sm rounded-lg block w-full p-2.5">
            </div>
            <div class="pt-3">
              <button type="submit" class="w-full text-black bg-white hover:bg-gray-400 font-medium rounded-lg text-sm py-2.5 text-center">
                Login
              </button>
            </div>
            <p class="text-sm font-light text-gray-500">
              Have not account yet?
              <a href="/register" class="font-medium text-white hover:underline">
                Register
              </a>
            </p>
            @error('login')
                <div class="font-sm text-red-600">{{ $message }}</div>
            @enderror
          </form>
    </div>
  </div>
@endsection