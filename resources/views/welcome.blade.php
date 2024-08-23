<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event gallery</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    @stack('head')
</head>
<body class="w-full bg-gray-100">
    <div id="app" class="w-full min-h-screen flex flex-col">
       <router-view></router-view>
    </div>
    @stack('scripts')
</body>
</html>