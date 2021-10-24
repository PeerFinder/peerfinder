<x-layout.account :title="__('account/avatar.title')">


    @if ($user->avatar)
    <div class="mb-5">
        <h2 class="block mb-2 font-medium">{{ __('account/avatar.current_avatar') }}</h2>

        <div class="space-y-5 p-5 border border-gray-300 inline-block rounded-md shadow-sm">
            <div class="text-center">
                <x-ui.user.avatar :user="$user" size="200" nocache="true" class="inline-block rounded-full" />
            </div>

            <x-account.form :action="route('account.avatar.destroy')" class="space-y-6 text-center">
                <div>
                    @csrf
                    @method('DELETE')
                    <x-ui.forms.button action="inform">{{ __('account/avatar.button_delete_avatar') }}</x-ui.forms.button>
                </div>
            </x-account.form>
        </div>

    </div>
    @endif

    <x-account.form :action="route('account.avatar.update')" class="space-y-6" enctype="multipart/form-data">
        <div>
            <x-ui.forms.input id="avatar" name="avatar" type="file" required>{{ __('account/avatar.field_avatar') }}</x-ui.forms.input>
        </div>

        <x-account.form-buttons>
            @csrf
            @method('PUT')
            <x-ui.forms.button>{{ __('account/avatar.button_upload_avatar') }}</x-ui.forms.button>
        </x-account.form-buttons>
    </x-account.form>






</x-layout.account>