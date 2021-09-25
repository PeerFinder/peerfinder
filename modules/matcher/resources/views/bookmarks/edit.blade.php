<x-matcher::layout.single :pg="$pg">

    <x-ui.card class="my-5" title="{{ __('matcher::peergroup.update_bookmarks_title') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        <x-ui.forms.form :action="route('matcher.bookmarks.update', ['pg' => $pg->groupname])">

            <editable-list class="p-2 space-y-2" :initial="{{ $bookmarks }}">
                <template v-slot:list-item="props">
                    <div class="flex items-center border p-2 rounded-md shadow-sm bg-gray-50">
                        <div class="flex-1 grid grid-cols-2 gap-2">
                            <div>
                                <input class="w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pf-midblue focus:border-transparent" v-model="props.item.data.url" name="url[]" type="url" placeholder="{{ __('matcher::peergroup.bookmark_url') }}"  :class="props.item.data.error.url.length && 'border-red-300 bg-red-50'" />
                            </div>
                            <div>
                                <input class="w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pf-midblue focus:border-transparent" v-model="props.item.data.title" name="title[]" type="text" placeholder="{{ __('matcher::peergroup.bookmark_title') }}" :class="props.item.data.error.title.length && 'border-red-300 bg-red-50'" />
                            </div>
                        </div>
                        <div class="ml-2">
                            <a @click.prevent="props.actionRemove(props.item)" href="#"><x-ui.icon name="trash" /></a>
                        </div>
                    </div>
                </template>

                <template v-slot:add-button="props">
                    <div class="border border-dashed p-2 rounded-md text-center">
                        <x-ui.forms.button tag="a" href="#" @click.prevent="props.actionAdd({title:'', url:'', error: {'title': [], 'url': []}})" action="create">{{ __('matcher::peergroup.button_add_bookmark') }}</x-ui.forms.button>
                    </div>
                </template>
            </editable-list>

            <div class="p-4 border-t">
                @csrf
                @method('PUT')

                <x-matcher::ui.edit-buttons :pg="$pg">{{ __('matcher::peergroup.button_update_bookmarks') }}</x-matcher::ui.edit-buttons>
            </div>
        </x-ui.forms.form>
    </x-ui.card>
</x-matcher::layout.single>