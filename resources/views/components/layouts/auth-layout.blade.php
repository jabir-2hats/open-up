<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ? "$title - TaskJet" : "TaskJet" }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-screen flex flex-col items-center justify-center">
    <header class="mb-8">
        <x-app-logo />
    </header>
    <main>
        {{ $slot }}
    </main>
</body>

</html>
