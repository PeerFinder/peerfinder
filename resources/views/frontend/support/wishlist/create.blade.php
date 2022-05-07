<x-layout.support>
    <x-slot name="title">
        {{ __('support/wishlist.title') }}
    </x-slot>

    <div>
        <div class="mb-4">@lang('support/wishlist.text_explanation')</div>

        <x-account.form :action="route('support.wishlist.store')" class="space-y-6">
            <div>
                <x-ui.forms.textarea id="body" value="{{ old('body') }}" name="body" rows="4">@lang('support/wishlist.field_body')</x-ui.forms.textarea>
            </div>

            <x-account.form-buttons>
                <input type="text" name="context" value="{{ $context }}" />

                @csrf
                @method('PUT')
                <x-ui.forms.button>@lang('support/support.button_send')</x-ui.forms.button>
            </x-account.form-buttons>
        </x-account.form>
    </div>
</x-layout.support>