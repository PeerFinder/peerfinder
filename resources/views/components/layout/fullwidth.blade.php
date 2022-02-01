<x-base.page :title="$title">
    <x-base.app>
        <x-ui.sections.header :user="$currentUser" />

        <x-ui.status />

        {{ $slot }}
        
        <x-ui.sections.footer />
    </x-base.app>
</x-base.page>
