<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <title>@yield('title')</title>
</head>
<body class="bg-black">
    <div class="text-white">
      @if (View::getSection('title')!='Login' && View::getSection('title')!='Register') 
        <div class="relative">
          <div class="bg-black h-20 border border-white border-b w-screen top-0 fixed text-white flex items-center px-5 z-[100]">
            <a href="/">
              <div class="text-xl font-bold">Monolith</div>
            </a>
            <form class="absolute left-[200px] w-[300px]" action="/">
              <input type="text" name="search" placeholder="Search film" autocomplete="off" aria-label="Search film" class="px-3 py-2 w-full font-semibold placeholder-gray-500 text-black rounded-2xl ring-2 ring-white">
            </form>
            @auth
              <a href="/logout" class="absolute right-5">
                <div class="bg-black text-white ease-in duration-200 hover:bg-white hover:text-black w-[100px] h-[50px]  font-semibold flex items-center justify-center">
                  <div>Logout</div>
                </div>
              </a>   
              <div class="absolute font-semibold right-[170px]">{{$user->username}}</div>
              <div class="absolute font-semibold right-[250px]">🪙 {{$user->balance}}</div>
            @endauth

            @auth
              <a href="/my_list/{{$user->id}}" class="absolute left-[520px]">
                <div class="bg-black text-white ease-in duration-200 hover:bg-white hover:text-black w-[100px] h-[50px]  font-semibold flex items-center justify-center">
                  <div>My List</div>
                </div>
              </a>
            @endauth
            
            @guest
            <a href="/login" class="absolute right-5">
              <div class="bg-black text-white ease-in duration-200 hover:bg-white hover:text-black w-[100px] h-[50px]  font-semibold flex items-center justify-center">
                <div>Login</div>
              </div>
            </a>
              {{-- <div class="absolute text-xl font-bold right-5">Login</div> --}}
            @endguest
          </div>
        </div>
      @endif
      
       @yield('content')
    </div>
</body>
</html>