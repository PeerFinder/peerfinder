<x-talk::layout.single :conversation="$conversation">
    <div class="p-4 border-b">
        <p>{{ __('talk::talk.select_users') }}</p>
    </div>

    <div class="p-4">
        <x-ui.forms.form :action="route('talk.selectAndRedirect')">

            <dropdown-input url="{{ route('profile.user.search') }}?name=$1" 
                        input-name="users" :max-selected="0" 
                        items-field="users" items-id="username" 
                        items-value="name" :lookup-delay="500"
                        placeholder="Enter Name...">

            </dropdown-input>

            <div class="mt-2">
                @csrf
                @method('POST')
                
                <x-ui.forms.button>{{ __('talk::talk.button_select') }}</x-ui.forms.button>
                
            </div>

        </x-ui.forms.form>
    </div>
    
</x-talk::layout.single>