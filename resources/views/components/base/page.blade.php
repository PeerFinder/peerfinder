<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - {{ config('app.name') }}</title>

    <script src="{{ mix('js/app.js') }}" defer></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <!-- TODO: Favicon -->
    <!-- TODO: Twitter Card -->

    <style>
        @font-face {
            font-family: 'Quicksand';
            src: url('{{ asset('/fonts/Quicksand-VariableFont_wght.ttf') }}') format('truetype');
        }
    </style>
</head>

{{ $slot }}

</html>