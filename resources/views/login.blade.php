@extends('layouts.app')

@section('title','Login')

@section('content')
  <div class="flex flex-col items-center justify-center h-screen">
    <div class="bg-black text-white h-[300px] w-[500px] px-[20px] py-[20px] font-semibold rounded-lg bg-opacity-20">
        <h3 class="text-3xl mb-5">Login</h3>
        <form class="space-y-5" method="POST" action="{{ route('post_login') }}">
            @csrf
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="username" id="username" autocomplete="off" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-white focus:outline-none focus:ring-0 focus:border-white peer" placeholder=" " required />
                <label for="username" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-white peer-focus:dark:text-white peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Username or Email</label>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <input type="password" name="password" id="password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-white focus:outline-none focus:ring-0 focus:border-white peer" placeholder=" " required />
                <label for="password" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-white peer-focus:dark:text-white peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>
            </div>
            <div class="pt-3">
              <button type="submit" class="w-full text-white bg-black hover:bg-white hover:text-black ease-in duration-200 font-medium rounded-lg text-sm py-2.5 text-center">
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