@props(['conversation'])

<div class="text-center">
@if ($conversation->isParticipant(auth()->user()))
    @can('leave', $conversation)
    
    @endcan
@else
    @can('join', $conversation)
    <x-ui.forms.form :action="route('talk.join', ['conversation' => $conversation->identifier])" method="post">
        @csrf
        <x-ui.forms.button action="execute">{{ __('talk::talk.button_join_conversation') }}</x-ui.forms.button>
    </x-ui.forms.form>
    @endcan
@endif
</div>