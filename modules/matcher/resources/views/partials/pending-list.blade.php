@if ($pending)
<x-ui.card class="after:bg-gradient-to-r after:from-red-400 after:to-red-600 after:h-1 after:block overflow-hidden" title="{{ __('matcher::peergroup.title_waiting_approval') }}" subtitle="{{ __('matcher::peergroup.group_administration_notice') }}">
    <div class="space-y-2 p-4">
        @foreach ($pending as $pending_membership)
        <div class="bg-gray-50 p-2 flex rounded-md">
            <div class="flex-1 flex items-center">
                <x-ui.user.avatar :user="$pending_membership->user" size="30" class="rounded-full mr-2" />
                <a href="{{ $pending_membership->user->profileUrl() }}">{{ $pending_membership->user->name }}</a>
            </div>
            <div>
                <x-ui.forms.form :action="route('matcher.membership.approve', ['pg' => $pg->groupname, 'username' => $pending_membership->user->username])" method="post" class="inline-block">
                    @csrf
                    <x-ui.forms.button class="mr-1">{{ __('matcher::peergroup.button_approve_member') }}</x-ui.forms.button>
                </x-ui.forms.form>
                <x-ui.forms.form :action="route('matcher.membership.decline', ['pg' => $pg->groupname, 'username' => $pending_membership->user->username])" method="post" class="inline-block">
                    @csrf
                    <x-ui.forms.button action="inform">{{ __('matcher::peergroup.button_decline_member') }}</x-ui.forms.button>
                </x-ui.forms.form>
            </div>
        </div>
        @endforeach
    </div>
</x-ui.card>
@endif