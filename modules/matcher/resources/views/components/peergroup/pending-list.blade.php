@if ($pending)
<div class="p-4 border-b">
    <h2 class="mb-2 font-semibold">{{ __('matcher::peergroup.title_waiting_approval') }}</h2>

    <div class="space-y-2">
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
</div>
@endif