<x-base.page :title="$title">
    <body class="bg-gray-100">
        <x-ui.sections.header :user="$currentUser" />

        <x-base.container class="mb-10 sm:px-3">
            {{ $slot }}
        </x-base.container>

        <x-ui.sections.footer />
    </body>
</x-base.page>
