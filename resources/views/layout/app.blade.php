@use('Illuminate\Support\Facades\Vite')

<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title') @yield('title') @else {{ $title }} @endif - {{ $appName }}</title>
    @stack('general')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@200;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/pjutils/assets/main.css') }}">

    @if (file_exists(base_path('vendor/patrikjak/starter/public/hot')))
        {{ Vite::useHotFile('/var/www/vendor/patrikjak/starter/public/hot') }}
        <link rel="stylesheet" href="{{ Vite::asset('resources/css/tailwind.css') }}">
        <link rel="stylesheet" href="{{ Vite::asset('resources/css/app.scss') }}">
        <script src="{{ Vite::asset('resources/js/main.ts') }}" defer type="module"></script>
    @else
        <link rel="stylesheet" href="{{ asset('vendor/pjstarter/assets/app.css') }}">
        <script src="{{ asset('vendor/pjstarter/assets/main.js') }}" defer type="module"></script>
        <link rel="stylesheet" href="{{ asset('vendor/pjstarter/assets/tailwind.css') }}">
    @endif

    @isset($icon)
        <link rel="icon" type="{{ $iconType }}" href="{{ asset($icon) }}">
    @endisset

    @stack('styles')
    @stack('scripts')
</head>
<body>

    <div class="base-layer">
        <x-pjstarter::navigation />

        <div class="content">
            <div class="header">
                <h1>{{ $title }}</h1>

                @isset($actions)
                    <div class="actions">
                        {{ $actions }}
                    </div>
                @endisset
            </div>

            <div class="body">
                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>