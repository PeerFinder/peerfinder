<x-matcher::layout.single :pg="$pg">

    <x-ui.card class="my-5" title="{{ __('matcher::peergroup.update_image_title') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        @if ($pg->image)
        <div class="p-4">
            <h2 class="block mb-2 font-medium">@lang('matcher::peergroup.current_image')</h2>
    
            <div class="space-y-5 p-5 border border-gray-300 inline-block rounded-md shadow-sm">
                <div class="text-center">
                    
                </div>
    
                <x-account.form :action="route('matcher.image.destroy', ['pg' => $pg->groupname])" class="space-y-6 text-center">
                    <div>
                        @csrf
                        @method('DELETE')
                        <x-ui.forms.button action="inform">@lang('matcher::peergroup.button_delete_image')</x-ui.forms.button>
                    </div>
                </x-account.form>
            </div>
        </div>
        @endif    

        <x-ui.forms.form :action="route('matcher.image.update', ['pg' => $pg->groupname])" enctype="multipart/form-data">

            <div class="p-4">
                <x-ui.forms.input id="iamge" name="image" type="file" required>@lang('matcher::peergroup.field_image')</x-ui.forms.input>
            </div>

            <div class="p-4 border-t">
                @csrf
                @method('PUT')

                <x-matcher::ui.edit-buttons :pg="$pg">@lang('matcher::peergroup.button_update_image')</x-matcher::ui.edit-buttons>
            </div>
        </x-ui.forms.form>
    </x-ui.card>
</x-matcher::layout.single>