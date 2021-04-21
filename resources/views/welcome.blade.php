<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <title>Virgool</title>

    <script>
        window.csrf_token = "{{ csrf_token() }}"
    </script>
    @auth
    <script>
        window.user= {
            id : '{{auth('sanctum')->user()->id}}',
            name : '{{auth('sanctum')->user()->name}}',
            profile_src : '{{auth('sanctum')->user()->profile_src}}',
            isVerify : '{{auth('sanctum')->user()->email_verified_at === null ? 0 : 1 }}'
        }
    </script>
    @endauth


</head>

<body>

    <div id="app">
        <app-component />
    </div>

    <script src="{{ asset('js/app.js') }}" defer></script>


</body>

</html>
