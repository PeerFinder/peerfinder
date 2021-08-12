<x-base.page :title="$title">
    <body class="bg-gray-100">
        <x-ui.sections.header :user="$currentUser" />

        <div class="w-full max-w-5xl mx-auto mb-10 px-3">
            {{ $slot }}
        </div>
    </body>
</x-base.page>
