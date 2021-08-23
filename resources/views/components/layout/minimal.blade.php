<x-base.page :title="$title">
    <x-base.app>
        <x-ui.sections.header :user="$currentUser" />

        <x-ui.status />

        <x-base.container class="mb-10 sm:px-3">
            {{ $slot }}
        </x-base.container>

        <x-ui.sections.footer />
    </x-base.app>
</x-base.page>
