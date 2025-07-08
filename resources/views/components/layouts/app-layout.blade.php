<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ? "$title - OpenUp" : 'OpenUp' }}</title>
    {{ $head ?? '' }}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="">
    <header class="mb-8">
        <div class="navbar bg-base-100 shadow-sm flex justify-between items-center px-4">
            <x-app-logo />
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-error btn-outline btn-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 17l5-5-5-5M19.8 12H9M13 22a10 10 0 1 1 0-20" />
                    </svg>
                </button>
            </form>
        </div>
    </header>
    <main class="px-4">
        {{ $slot }}
    </main>
</body>

</html>
