<x-layout.account :title="__('account/account.title')">
    <h2 class="font-semibold text-lg mb-2">{{ __('account/account.title_delete_account') }}</h2>
    
    <p class="mb-2">{{ __('account/account.deletion_warning') }}</p>

    <x-account.form :action="route('account.account.destroy')">
        @if ($user->ownsPeergroups())
        <div class="p-3 border rounded-md mt-4">
            <p class="text-red-500">
                <x-ui.icon name="exclamation" />{{ __('account/account.owning_peergroups_warning') }}
            </p>
            <h3 class="font-semibold mt-3">{{ __('account/account.title_groups') }}</h3>
            <ul class="mt-2">
            @foreach ($user->peergroups()->get() as $peergroup)
                <li><x-ui.link href="{{ $peergroup->getUrl() }}" target="_blank">{{ $peergroup->title }}</x-ui.link></li>
            @endforeach
            </ul>
        </div>
        @else
        
        <p class="mt-4 mb-4 font-semibold">{{ __('account/account.confirm_with_password') }}</p>

        <div class="max-w-xs mb-4">
            <x-ui.forms.input id="password" name="password" type="password" autocomplete="current-password" required>{{ __('auth.field_password') }}</x-ui.forms.input>
        </div>

        <x-account.form-buttons>
            @csrf
            @method('DELETE')
            <x-ui.forms.button action="destroy">{{ __('account/account.button_delete_account') }}</x-ui.forms.button>
        </x-account.form-buttons>
        @endif
    </x-account.form>
</x-layout.account>