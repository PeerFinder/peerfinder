<body class="bg-gray-100">
    <div id="app" v-cloak>
        {{ $slot }}
    </div>
    <script src="{{ mix('js/app.js') }}" defer></script>
    @include('cookie-consent::index')
</body>