@props(['twittercard' => null, 'title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="application-name" content="{{ config('app.name') }}">
	<meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <meta name="apple-mobile-web-app-status-bar-style" content="#1F303A">
    <link rel="icon" type="image/png" href="{{ Urler::versioned_asset('/images/favicon-16x16.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ Urler::versioned_asset('/images/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ Urler::versioned_asset('/images/favicon-96x96.png') }}" sizes="96x96">
    <link rel="apple-touch-icon" href="{{ Urler::versioned_asset('/images/apple-touch-icon.png') }}">

    {{ $twittercard }}

    <title>{{ $title }} - {{ config('app.name') }}</title>

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <style>
        @font-face {
            font-family: 'Quicksand';
            src: url('{{ asset("/fonts/quicksand/Quicksand-Light.ttf") }}') format('truetype');
            font-weight: 300;
        }
        @font-face {
            font-family: 'Quicksand';
            src: url('{{ asset("/fonts/quicksand/Quicksand-Regular.ttf") }}') format('truetype');
            font-weight: 400;
        }
        @font-face {
            font-family: 'Quicksand';
            src: url('{{ asset("/fonts/quicksand/Quicksand-Medium.ttf") }}') format('truetype');
            font-weight: 500;
        }
        @font-face {
            font-family: 'Quicksand';
            src: url('{{ asset("/fonts/quicksand/Quicksand-SemiBold.ttf") }}') format('truetype');
            font-weight: 600;
        }     
        @font-face {
            font-family: 'Quicksand';
            src: url('{{ asset("/fonts/quicksand/Quicksand-Bold.ttf") }}') format('truetype');
            font-weight: 700;
        }
        @font-face {
            font-family: 'RobotoSlab';
            src: url('{{ asset("/fonts/Roboto_Slab/static/RobotoSlab-Light.ttf") }}') format('truetype');
            font-weight: 300;
        }
        @font-face {
            font-family: 'RobotoSlab';
            src: url('{{ asset("/fonts/Roboto_Slab/static/RobotoSlab-Regular.ttf") }}') format('truetype');
            font-weight: 400;
        }
        @font-face {
            font-family: 'RobotoSlab';
            src: url('{{ asset("/fonts/Roboto_Slab/static/RobotoSlab-Bold.ttf") }}') format('truetype');
            font-weight: 700;
        }
    </style>

    <!-- Matomo -->
    <script>
        var _paq = window._paq = window._paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
        var u="{{ config('app.matomo_tracking_url') }}";
        _paq.push(['setTrackerUrl', u+'matomo.php']);
        _paq.push(['setSiteId', '{{ config('app.matomo_tracking_id') }}']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
        })();
    </script>
    <!-- End Matomo Code -->
</head>

{{ $slot }}

</html>