<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <title>Menu Creator</title>
</head>
<body class="flex w-full h-full bg-gray-700 items-center justify-center">
    <form id="loginBox" class="gap-1" method="POST" action="{{ route('loginAuth') }}">
        @csrf
        <p class="w-full text-center text-5xl font-sans">Menu Creator</p>
        <p class="text-orange-500 text-2xl py-5">Vitajte, prihláste sa</p>

        <label>Užívateľské meno <span class="text-red-500">*</span></label>
        <input type="text" class="loginInput" required>

        <label>Heslo <span class="text-red-500">*</span></label>
        <input type="text" class="loginInput" required>

        <button class="h-10 bg-orange-500 text-white mt-7 px-4 rounded">Prihláste sa</button>
    </form>
</body>
</html>
