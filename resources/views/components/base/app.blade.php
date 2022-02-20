<body class="bg-gray-100">
    <div id="app">
        {{ $slot }}
    </div>
    <script src="{{ mix('js/app.js') }}" defer></script>
    @include('cookie-consent::index')
    <!-- {{ sprintf('%d', (microtime(true) - LARAVEL_START)*1000) }} -->
</body>