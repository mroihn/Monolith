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
            <div class="text-xl font-bold">Monolith</div>
            <form class="absolute left-[200px] w-[700px]" action="/">
              <input type="text" name="search" placeholder="Search film" autocomplete="off" aria-label="Search film" class="px-3 py-2 w-full font-semibold placeholder-gray-500 text-black rounded-2xl ring-2 ring-gray-300 focus:ring-gray-500 focus:ring-2">
            </form>
            <div class="absolute text-xl font-bold right-5">@yield('nama')</div>
          </div>
        </div>
      @endif
      
       @yield('content')
    </div>
</body>
</html>