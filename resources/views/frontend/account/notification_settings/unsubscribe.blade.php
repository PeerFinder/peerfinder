<x-layout.auth :title="__('account/notification_settings.unsubscribe_title')">
    <x-auth.card>
        <x-slot name="title">
            <x-auth.headline>{{ __('account/notification_settings.unsubscribe_title') }}</x-auth.headline>
        </x-slot>
        <p class="p-6 text-center">{{ __('account/notification_settings.unsubscribe_message') }}</p>
    </x-auth.card>
</x-layout.auth>