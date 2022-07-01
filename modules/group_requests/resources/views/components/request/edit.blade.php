@props(['group_request' => null])

<div class="md:grid md:grid-cols-10">
    <div class="sm:col-span-6 lg:col-span-7">
        <x-ui.card title="{{ $group_request ? __('group_requests::group_requests.edit_title') : __('group_requests::group_requests.create_title') }}" class="mt-5">
            <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

            <x-ui.forms.form :action="$group_request ? route('group_requests.edit', ['identifier' => $group_request->identifier]) : route('group_requests.create')">
                <x-ui.forms.section>
                    <x-ui.forms.section-body>
                        <x-ui.forms.input id="title" value="{{ old('title', $group_request ? $group_request->title : '') }}" name="title" type="text" required>{{ __('group_requests::group_requests.field_title') }}</x-ui.forms.input>
                    </x-ui.forms.section-body>

                    <x-ui.forms.section-body>
                        <x-ui.forms.textarea id="description" value="{{ old('description', $group_request ? $group_request->description : '') }}" name="description" rows="5" required>
                            {{ __('group_requests::group_requests.field_description') }}
                        </x-ui.forms.textarea>
                    </x-ui.forms.section-body>
                </x-ui.forms.section>

                <x-ui.forms.section-body>
                    <x-ui.forms.multi-checkbox :selection="\Matcher\Models\Language::all()" key="code" :default="$group_request ? $group_request->languages : null" name="languages" required="true">
                        {{ __('group_requests::group_requests.field_languages') }}</x-ui.forms.multi-checkbox>
                </x-ui.forms.section-body>

                <x-ui.forms.form-navigation>
                    @method('PUT')
                    @if ($group_request)
                        <x-group_requests::ui.edit-buttons :group_request="$group_request">{{ __('group_requests::group_requests.button_save_request') }}</x-group_requests::ui.edit-buttons>
                    @else
                        <x-group_requests::ui.edit-buttons :group_request="$group_request" action="create">{{ __('group_requests::group_requests.button_create_request') }}</x-group_requests::ui.edit-buttons>
                    @endif
                </x-ui.forms.form-navigation>
            </x-ui.forms.form>
        </x-ui.card>
    </div>
</div>
