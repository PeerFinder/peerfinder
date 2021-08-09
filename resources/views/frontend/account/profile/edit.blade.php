<x-layout.account :title="__('account/profile.title')">
    <x-account.form :action="route('account.profile.update')" class="space-y-6">
        <div class="max-w-xs">
            <x-ui.forms.input id="name" value="{{ old('name', $user->name) }}" name="name" type="text"
                autocomplete="name" required>{{ __('account/profile.field_name') }}</x-ui.forms.input>
        </div>

        <div>
            <x-ui.forms.input id="slogan" value="{{ old('slogan', $user->slogan) }}" name="slogan" type="text">
                {{ __('account/profile.field_slogan') }}</x-ui.forms.input>
        </div>

        <div>
            <x-ui.forms.textarea id="about" value="{{ old('about', $user->about) }}" name="about" rows="5">
                {{ __('account/profile.field_about') }}</x-ui.forms.textarea>
        </div>

        <div>
            <x-ui.forms.input id="homepage" value="{{ old('homepage', $user->homepage) }}" name="homepage">
                {{ __('account/profile.field_homepage') }}</x-ui.forms.input>
        </div>

        @foreach ($plattforms as $plattform)
            <div>
                <x-ui.forms.input id="{{ $plattform }}_profile" value="{{ old($plattform . '_profile', $user->getAttribute($plattform . '_profile')) }}" name="{{ $plattform }}_profile">
                    {{ __('account/profile.field_' . $plattform) }}</x-ui.forms.input>
            </div>
        @endforeach

        <div>
            @csrf
            @method('PUT')
            <x-ui.forms.button>{{ __('account/profile.button_change_profile') }}</x-ui.forms.button>
        </div>
    </x-account.form>
</x-layout.account>
