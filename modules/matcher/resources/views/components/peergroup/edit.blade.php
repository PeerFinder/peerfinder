@props(['pg' => null, 'errors' => null, 'groupTypes'])

<x-ui.card title="{{ $pg->isDirty() ? __('matcher::peergroup.new_peergroup_title') : __('matcher::peergroup.edit_title') }}">
    <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

    <x-ui.forms.form :action="$pg->isDirty() ? route('matcher.create') : route('matcher.edit', ['pg' => $pg->groupname])">
        <x-ui.forms.section>
            <x-ui.forms.section-body>
                <x-ui.forms.select name="group_type" id="group_type" :options="$groupTypes" value="{{ old('group_type', $pg->groupType ? $pg->groupType->identifier : null) }}">{{ __('matcher::peergroup.field_group_type') }} <x-ui.link href="{{ route('matcher.group_types') }}" class="text-sm ml-2" target="_blank">@lang('matcher::peergroup.more_about_types')</x-ui.link></x-ui.forms.select>
            </x-ui.forms.section-body>

            <x-ui.forms.section-body>
                <x-ui.forms.input id="title" value="{{ old('title', $pg->title) }}" name="title" type="text" required>{{ __('matcher::peergroup.field_title') }}</x-ui.forms.input>
            </x-ui.forms.section-body>

            <x-ui.forms.section-body>
                <x-ui.forms.textarea id="description" value="{{ old('description', $pg->description) }}" name="description" rows="5" required>{{ __('matcher::peergroup.field_description') }}</x-ui.forms.textarea>
            </x-ui.forms.section-body>

            <x-ui.forms.section-body>
                <dropdown-input url="{{ route('matcher.tags.search') }}?tag=$1"
                    input-name="search_tags" :max-selected="0" 
                    items-field="tags" items-id="slug" 
                    :min-search-length="0"
                    items-value="name" :lookup-delay="500"
                    placeholder="{{ __('matcher::peergroup.enter_tag') }}" :items="{{ Matcher::tagsForForm('search_tags', $pg->tags) }}"
                    label="{{ __('matcher::peergroup.field_tags') }}" :strict="false">

                </dropdown-input>
            </x-ui.forms.section-body>

            <x-ui.forms.section-heading>@lang('matcher::peergroup.section_heading_organization')</x-ui.forms.section-heading>

            <x-ui.forms.section-body class="flex space-x-6">
                <div class="w-1/3">
                    <x-ui.forms.input id="begin" value="{{ old('begin', $pg->begin->format('Y-m-d')) }}" name="begin" type="date" required>{{ __('matcher::peergroup.field_begin') }}</x-ui.forms.input>
                </div>
                <div>
                    <x-ui.forms.input id="limit" value="{{ old('limit', $pg->limit) }}" name="limit" type="number" min="2" max="{{ config('matcher.max_limit') }}" class="w-20" required>{{ __('matcher::peergroup.field_limit') }}</x-ui.forms.input>
                </div>
            </x-ui.forms.section-body>

            <x-ui.forms.section-body>
                <conditional-elements trigger="virtual" class="space-y-6">
                    <template v-slot="props">
                        <div>
                            <x-ui.forms.checkbox id="virtual" default="{{ $pg->virtual }}" name="virtual">{{ __('matcher::peergroup.field_virtual') }}</x-ui.forms.checkbox>
                        </div>
                        <div v-bind:class="{ 'hidden': (props.state) }">
                            <x-ui.forms.input id="location" value="{{ old('location', $pg->location) }}" name="location" type="text">{{ __('matcher::peergroup.field_location') }}</x-ui.forms.input>
                        </div>
                        <div v-bind:class="{ 'hidden': (!props.state) }">
                            <x-ui.forms.input id="meeting_link" value="{{ old('meeting_link', $pg->meeting_link) }}" name="meeting_link" type="text">{{ __('matcher::peergroup.field_meeting_link') }}</x-ui.forms.input>
                        </div>
                    </template>
                </conditional-elements>
            </x-ui.forms.section-body>
            
            <x-ui.forms.section-heading>@lang('matcher::peergroup.section_heading_settings')</x-ui.forms.section-heading>

            <x-ui.forms.section-body>
                <x-ui.forms.multi-checkbox :selection="\Matcher\Models\Language::all()" key="code" :default="$pg->languages" name="languages" required="true">{{ __('matcher::peergroup.field_languages') }}</x-ui.forms.multi-checkbox>
            </x-ui.forms.section-body>

            <x-ui.forms.section-body>
                <x-ui.forms.checkbox id="private" default="{{ $pg->private }}" name="private">{{ __('matcher::peergroup.field_private') }}</x-ui.forms.checkbox>
            </x-ui.forms.section-body>

            <x-ui.forms.section-body>
                <x-ui.forms.checkbox id="with_approval" default="{{ $pg->with_approval }}" name="with_approval">{{ __('matcher::peergroup.field_with_approval') }}</x-ui.forms.checkbox>
            </x-ui.forms.section-body>
        </x-ui.forms.section>

        <x-ui.forms.form-navigation>
            @method('PUT')
            @if ($pg->isDirty())
            <x-matcher::ui.edit-buttons :pg="$pg" action="create">{{ __('matcher::peergroup.button_create_peergroup') }}</x-matcher::ui.edit-buttons>
            @else
            <x-matcher::ui.edit-buttons :pg="$pg">{{ __('matcher::peergroup.button_save_peergroup') }}</x-matcher::ui.edit-buttons>
            @endif
        </x-ui.forms.form-navigation>
    </x-ui.forms.form>
</x-ui.card>