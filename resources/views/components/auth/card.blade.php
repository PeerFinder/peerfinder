<!-- Auth Card -->
<div class="content min-h-screen flex items-center justify-center">
    <div class="max-w-sm w-full flex flex-col items-center space-y-6 mb-12 items-stretch">
        <!-- Branding -->
        <div class="text-center">
            <img src="images/peerfinder_logo.png" srcset="images/peerfinder_logo@2x.png 2x" class="inline-block w-9" />
            <span class="block text-xl mt-2">{{ config('app.name') }}</span>
        </div>

        <!-- TODO: Session Status -->

        <!-- Title -->
        <div class="text-center">
            {{ $title }}
        </div>

        <!-- Form Card -->
        <div class="bg-white shadow after:bg-gradient-to-r after:from-yellow-400 after:to-yellow-600 after:h-1 after:block rounded-md overflow-hidden">
            @if ($errors->any())
                <div class="bg-red-300 px-5 py-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>
</div>