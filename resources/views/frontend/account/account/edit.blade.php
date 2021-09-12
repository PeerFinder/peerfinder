<x-layout.account :title="__('account/account.title')">
    <x-account.form :action="route('account.account.destroy')" class="space-y-6">
        @if ($user->ownsPeergroups())
        <div>
            <p class="text-red-500">
                <x-ui.icon name="exclamation" />{{ __('account/account.owning_peergroups_warning') }}
            </p>
            <h2 class="font-semibold mt-3">{{ __('account/account.title_groups') }}</h2>
            <ul class="mt-2">
            @foreach ($user->peergroups()->get() as $peergroup)
                <li><x-ui.link href="{{ $peergroup->getUrl() }}" target="_blank">{{ $peergroup->title }}</x-ui.link></li>
            @endforeach
            </ul>
        </div>
        @else
        <p>{{ __('account/account.deletion_warning') }}</p>

        <div class="max-w-xs">
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