@props(['conversation'])

@if (auth()->user() && $conversation->isParticipant(auth()->user()))
    @can('leave', $conversation)
    <div class="text-center border-t p-4 bg-gray-50">
        <x-ui.forms.form :action="route('talk.leave', ['conversation' => $conversation->identifier])" method="post">
            @csrf
            <x-ui.forms.button action="destroy">{{ __('talk::talk.button_leave_conversation') }}</x-ui.forms.button>
        </x-ui.forms.form>
    </div>
    @endcan
@else
    @can('join', $conversation)
    <div class="text-center border-t p-4 bg-gray-50">
        <x-ui.forms.form :action="route('talk.join', ['conversation' => $conversation->identifier])" method="post">
            @csrf
            <x-ui.forms.button action="execute">{{ __('talk::talk.button_join_conversation') }}</x-ui.forms.button>
        </x-ui.forms.form>
    </div>
    @endcan
@endif