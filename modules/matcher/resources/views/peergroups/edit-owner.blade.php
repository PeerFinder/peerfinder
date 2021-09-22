<x-matcher::layout.single :pg="$pg">

    <x-ui.card class="my-2 sm:my-5" title="{{ __('matcher::peergroup.change_owner') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        <x-ui.forms.form :action="route('matcher.editOwner', ['pg' => $pg->groupname])">
            <div class="p-4">
                <p>{{ __('matcher::peergroup.change_owner_notice') }}</p>
            </div>

            <div class="p-4 space-y-2 w-1/2">
            @forelse ($members as $member)
                <div>
                    <input class="peer hidden" id="owner-{{ $loop->index }}" type="radio" name="owner" value="{{ $member->username }}" />
                    <div class="flex border items-center pl-2 peer-checked:bg-white peer-checked:border-pf-midorange bg-gray-50 border-gray-300 hover:bg-gray-100 rounded-md peer-checked:shadow-md">
                        <x-ui.user.avatar :user="$member" size="30" class="rounded-full" />
                        <label for="owner-{{ $loop->index }}" class="block pr-3 py-3 flex-1 ml-2 cursor-pointer">{{ $member->name }}</label>
                    </div>
                </div>
            @empty
            {{ __('matcher::peergroup.change_owner_no_members') }}
            @endforelse
            </div>

            <div class="mt-2 p-4 border-t">
                @csrf
                @method('PUT')
                <x-matcher::ui.edit-buttons :pg="$pg">{{ __('matcher::peergroup.button_change_owner') }}</x-matcher::ui.edit-buttons>
            </div>
        </x-ui.forms.form>
    </x-ui.card>
</x-matcher::layout.single>
