<x-group_requests::layout.minimal :title="__('group_requests::group_requests.delete_title')">
    <div class="md:grid md:grid-cols-10">
        <div class="sm:col-span-6 lg:col-span-7">
            <x-ui.card title="{{ __('group_requests::group_requests.delete_title') }}" class="mt-5">            
                
                <x-ui.forms.form :action="route('group_requests.delete', ['group_request' => $group_request->identifier])">

                    <div class="p-4 pb-2">
                        <x-group_requests::request.card :group_request="$group_request" />
                    </div>

                    <x-ui.forms.section>
                        <x-ui.forms.section-body>
                            @lang('group_requests::group_requests.delete_warning')
                        </x-ui.forms.section-body>
                    </x-ui.forms.section>
    
                    <x-ui.forms.form-navigation>
                        @method('DELETE')
                        <x-group_requests::ui.edit-buttons :group_request="$group_request" action="destroy">{{ __('group_requests::group_requests.button_destroy_request') }}</x-group_requests::ui.edit-buttons>
                    </x-ui.forms.form-navigation>
                </x-ui.forms.form>
            </x-ui.card>
        </div>
    </div>
</x-group_requests::layout.minimal>
