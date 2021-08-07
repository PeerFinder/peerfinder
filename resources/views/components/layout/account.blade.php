<x-base.page :title="$title">

    <body class="bg-gray-100">

        <div class="w-full max-w-5xl mx-auto">
            <h1 class="mt-10 mb-5 text-3xl">{{ __('account/account.title') }}</h1>

            <div class="bg-white shadow-md after:bg-gradient-to-r after:from-yellow-400 after:to-yellow-600 after:h-1 after:block sm:rounded-md overflow-hidden">
                
                <div class="grid grid-cols-5">
                    <div class="col-span-1">
                        <div class="p-5">Menu</div>
                    </div>
                    
                    <div class="col-span-4">
                        <h2 class="text-xl px-10 pt-10">{{ $title }}</h2>

                        <x-account.status />

                        @if ($errors->any())
                            <x-account.flash class="bg-red-300 border-red-500">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </x-account.flash>
                        @endif
                                    
                        <div class="p-10">
                            {{ $slot }}
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </body>
</x-base.page>