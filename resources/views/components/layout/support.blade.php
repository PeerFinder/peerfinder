<x-base.page :title="$title">
    <x-base.app>
        <x-ui.sections.header :user="$currentUser" />

        <x-ui.status />

        <x-base.container class="mb-10 sm:px-3 pt-10 min-h-screen">
            <div class="bg-white shadow-sm sm:rounded-md overflow-hidden">
                <div class="p-5 lg:p-10 bg-gray-50 border-b">
                    <x-ui.h1>@lang('support/support.title')</x-ui.h1>
                </div>

                <div class="my-5 md:my-10 lg:my-20 mx-5 md:mx-20 lg:mx-40">
                    <h2 class="text-xl font-semibold mb-10">{{ $title }}</h2>

                    <x-ui.errors :errors="$errors" class="my-10 p-3" />

                    <div>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </x-base.container>

        <x-ui.sections.footer />
    </x-base.app>
</x-base.page>
