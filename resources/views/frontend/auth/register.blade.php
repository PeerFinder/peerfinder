<x-layout.auth :title="__('auth.register_title')">
    <x-auth.card>
        <x-slot name="title">
            <x-auth.headline>{{ __('auth.register_account') }}</x-auth.headline>

            @if (Route::has('register'))
            <p>{{ __('auth.or') }}
                <x-ui.link href="{{ route('login') }}" class="font-bold">{{ __('auth.sign_in') }}</x-ui.link>
            </p>
            @endif
        </x-slot>

        <x-auth.form action="{{ route('register') }}" class="space-y-4">
            <div>
                <x-ui.forms.input id="name" value="{{ old('name') }}" name="name" type="text" autocomplete="name" required>{{ __('auth.field_name') }}</x-ui.forms.input>
            </div>            
            <div>
                <x-ui.forms.input id="email" value="{{ old('email') }}" name="email" type="email" autocomplete="email" required>{{ __('auth.field_email') }}</x-ui.forms.input>
            </div>
            <div>
                <x-ui.forms.input id="password" name="password" type="password" autocomplete="new-password" required>{{ __('auth.field_password') }}</x-ui.forms.input>
            </div>
            <div>
                <x-ui.forms.input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>{{ __('auth.field_password_confirmation') }}</x-ui.forms.input>
            </div>
            <div class="prose">
                <p>{!! __('auth.accepting_policy', ['terms' => route('page.show', ['slug' => 'terms-of-service', 'language' => app()->getLocale()]), 'privacy' => route('page.show', ['slug' => 'privacy-policy', 'language' => app()->getLocale()])]) !!}</p>
            </div>
            <div class="text-center">
                @csrf
                <timezone-select></timezone-select>
                
                <x-ui.forms.button class="w-full" action="create">{{ __('auth.button_register') }}</x-ui.forms.button>
            </div>
        </x-auth.form>
    </x-auth.card>
</x-layout.auth>