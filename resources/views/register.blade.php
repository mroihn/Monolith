@extends('app')

@section('content')
  <div class="flex flex-col items-center justify-center h-screen">
    <div class="bg-black text-white h-[620px] w-[500px] px-[20px] py-[20px] font-semibold rounded-lg bg-opacity-20">
        <h3 class="text-3xl">Register</h3>
        <form class="space-y-2" method="POST" action="{{ route('post_register') }}">
            @csrf
            <div>
              <label for="first_name" class="text-sm font-medium">First Name</label>
              <input type="text" name="first_name" id="first_name" placeholder="Type your first name" class="bg-white border border-black
               text-black sm:text-sm rounded-lg block w-full p-2.5">
            </div>
            <div>
              <label for="last_name" class="text-sm font-medium">Last Name</label>
              <input type="text" name="last_name" id="last_name" placeholder="Type your last name" class="bg-white border border-black
               text-black sm:text-sm rounded-lg block w-full p-2.5">
            </div>
            <div>
              <label for="username" class="text-sm font-medium">Username</label>
              <input type="text" name="username" id="username" placeholder="Type your username" class="bg-white border border-black
               text-black sm:text-sm rounded-lg block w-full p-2.5">
            </div>
            <div>
              <label for="email" class="text-sm font-medium">Email</label>
              <input type="email" name="email" id="email" placeholder="Type your email" class="bg-white border border-black
               text-black sm:text-sm rounded-lg block w-full p-2.5">
            </div>
            <div>
              <label for="password" class="text-sm font-medium">Password</label>
              <input type="password" name="password" id="password" placeholder="Type your password" class="bg-white border border-black
               text-black sm:text-sm rounded-lg block w-full p-2.5">
            </div>
            <div>
              <label for="password_confirmation" class="text-sm font-medium">Password Confirmation</label>
              <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Type your password" class="bg-white border border-black
               text-black sm:text-sm rounded-lg block w-full p-2.5">
            </div>
            <div class="pt-3">
              <button type="submit" class="w-full text-black bg-white hover:bg-gray-400 font-medium rounded-lg text-sm py-2.5 text-center">
                Register
              </button>
            </div>
            <p class="text-sm font-light text-gray-500">
              Already have an account?
              <a href="/login" class="font-medium text-white hover:underline">
                Login
              </a>
            </p>
            @error('register')
                <div class="font-sm text-red-600">{{ $message }}</div>
            @enderror
            @if (session('success'))
                <div class="font-sm text-emerald-400">
                    {{ session('success') }}
                </div>
            @endif
          </form>
    </div>
  </div>
@endsection