<!-- Auth Card -->
<div class="content min-h-screen flex items-center justify-center">
    <div class="sm:max-w-sm w-full flex flex-col space-y-5 mb-12 items-stretch">
        <!-- Branding -->
        <div class="text-center">
            <a href="{{ route('index') }}" class="inline-flex items-center space-x-2 mt-2">
                <img src="{{ Urler::versioned_asset('/images/peerfinder_logo.png') }}" srcset="{{ Urler::versioned_asset('/images/peerfinder_logo@2x.png') }} 2x" class="inline-block w-9 mt-2" />
                <span class="block text-2xl mt-2">{{ config('app.name') }}</span>
            </a>
        </div>

        <!-- Title -->
        <div class="text-center">
            {{ $title }}
        </div>

        <!-- Form Card -->
        <div class="bg-white shadow-sm after:bg-gradient-to-r after:from-yellow-400 after:to-yellow-600 after:h-1 after:block sm:rounded-md overflow-hidden">
            @if ($errors->any())
                <x-auth.flash class="bg-red-300">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-auth.flash>
            @endif

            {{ $slot }}
        </div>

        @isset($aftercard)
        <div class="bg-white shadow-sm after:block sm:rounded-md overflow-hidden">
            {{ $aftercard }}
        </div>
        @endisset

        <!-- Additional navigation -->
        <x-auth.mini-nav />
    </div>
</div>