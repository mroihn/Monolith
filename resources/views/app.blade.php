<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
</head>
<body>
    <div class="bg-gradient-to-r from-zinc-800 to-slate-600 h-screen">
        @yield('content')
    </div>
</body>
</html>