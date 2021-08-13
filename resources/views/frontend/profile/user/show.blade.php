<x-layout.minimal>
    <x-slot name="title">
        {{ $user->name }}
    </x-slot>

    <x-ui.card class="mt-10">
        <div class="grid grid-cols-12">
            <div class="visual col-span-4">
                <div class="p-10 flex flex-col items-center">
                    <div class="image">
                        <x-ui.user.avatar :user="$user" class="rounded-full text-gray-400" size="200" />
                    </div>
                </div>
            </div>
            <div class="information col-span-8">
                <div class="pr-10 py-12">
                    <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                    @if ($user->slogan)
                    <p class="text-gray-400 mb-4">{{ $user->slogan }}</p>
                    @endif

                    <nav class="social-bookmarks mb-4 space-x-2">
                        <a href="#">Homepage</a>
                        <a href="#">Twitter</a>
                        <a href="#">Facebook</a>
                        <a href="#">LinkedIn</a>
                        <a href="#">Xing</a>
                    </nav>

                    @if ($user->about)
                        <p>{{ $user->about }}</p>                        
                    @endif
                </div>
            </div>
        </div>
    </x-ui.card>
</x-layout.minimal>