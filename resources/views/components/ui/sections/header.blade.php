<nav id="header" class="shadow bg-gray-800">
    <div class="max-w-5xl container mx-auto px-3 flex justify-between py-3 items-center flex-row">
        <div class="logo">
            <a href="{{ route('dashboard.index') }}" class="flex items-center">
                <img src="{{ asset('/images/peerfinder_logo.png') }}" srcset="{{ asset('/images/peerfinder_logo@2x.png') }}" 2x" class="w-7" />
                <div class="text-2xl ml-2 text-gray-300">{{ config('app.name') }}</div>
            </a>
        </div>

        <div class="flex items-center md:space-x-6 flex-row">
            <x-ui.user.avatar :user="$user" class="ml-2 w-10 h-10 rounded-full text-gray-400" />
        </div>
    </div>
</nav>