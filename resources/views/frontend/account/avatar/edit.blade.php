<x-layout.account :title="__('account/avatar.title')">


    @if ($user->avatar)
    <div class="mb-5">
        <x-account.form :action="route('account.avatar.destroy')" class="space-y-6">
            <div>
                @csrf
                @method('DELETE')
                <x-ui.forms.button action="destroy">{{ __('account/avatar.button_delete_avatar') }}</x-ui.forms.button>
            </div>
        </x-account.form>
    </div>
    @endif

    <x-account.form :action="route('account.avatar.update')" class="space-y-6" enctype="multipart/form-data">
        <div>
            <x-ui.forms.input id="avatar" name="avatar" type="file" required>{{ __('account/avatar.field_avatar') }}</x-ui.forms.input>
        </div>
        
        <div>
            @csrf
            @method('PUT')
            <x-ui.forms.button>{{ __('account/avatar.button_upload_avatar') }}</x-ui.forms.button>
        </div>
    </x-account.form>






</x-layout.account>