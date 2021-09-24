<x-matcher::layout.single :pg="$pg">

    <x-ui.card class="my-5" title="{{ __('matcher::peergroup.update_bookmarks_title') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        <x-ui.forms.form :action="route('matcher.bookmarks.update', ['pg' => $pg->groupname])">

            <bookmarks-editor class="p-2 space-y-2">
                <template v-slot:item="props">
                    <div class="flex items-center border p-2 rounded-md shadow-sm">
                        <div class="flex-1 grid grid-cols-2 gap-2">
                            <div>
                                <x-ui.forms.input name="url[]" type="url" required>{{ __('matcher::peergroup.bookmark_url') }}</x-ui.forms.input>
                            </div>
                            <div>
                                <x-ui.forms.input name="title[]" type="text">{{ __('matcher::peergroup.bookmark_title') }}</x-ui.forms.input>
                            </div>
                        </div>
                        <div class="ml-2">
                            <button @click.prevent="props.actionRemove"><x-ui.icon name="trash" /></button>
                        </div>
                    </div>
                </template>

                <template v-slot:add-button="props">
                    <div class="border mt-2 border-dashed p-2 rounded-md text-center">
                        <x-ui.forms.button @click.prevent="props.actionAdd" action="create">{{ __('matcher::peergroup.button_add_bookmark') }}</x-ui.forms.button>
                    </div>
                </template>
            </bookmarks-editor>

            {{--
            <div class="p-2 space-y-2">
                @foreach ($bookmarks as $i => $bookmark)
                <div id="bookmark-{$i}" class="flex items-center border p-2 rounded-md shadow-sm">
                    <div class="flex-1 grid grid-cols-2 gap-2">
                        <div>
                            <x-ui.forms.input name="url[]" type="url" value="{{ $bookmark['url'] }}" required>{{ __('matcher::peergroup.bookmark_url') }}</x-ui.forms.input>
                        </div>
                        <div>
                            <x-ui.forms.input name="title[]" type="text" value="{{ $bookmark['title'] }}">{{ __('matcher::peergroup.bookmark_title') }}</x-ui.forms.input>
                        </div>
                    </div>
                    <div class="ml-2">
                        <button><x-ui.icon name="trash" /></button>
                    </div>
                </div>
                @endforeach

                <div class="border mt-2 border-dashed p-2 rounded-md text-center">
                    <x-ui.forms.button tag="a" href="#" action="create">{{ __('matcher::peergroup.button_add_bookmark') }}</x-ui.forms.button>
                </div>
            </div>
            --}}

            <div class="p-4 border-t">
                @csrf
                @method('PUT')

                <x-matcher::ui.edit-buttons :pg="$pg">{{ __('matcher::peergroup.button_update_bookmarks') }}</x-matcher::ui.edit-buttons>
            </div>
        </x-ui.forms.form>
    </x-ui.card>
</x-matcher::layout.single>