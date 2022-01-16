<x-talk::layout.single :conversation="$conversation">
    <div class="p-4 border-b">
        <p>{{ __('talk::talk.select_users') }}</p>
    </div>

    <div class="p-4">

        <dropdown-input url="{{ route('profile.user.search') }}?name=$1" input-name="users" :max-selected="0" items-field="users" items-id="username" items-value="name" :lookup-delay="500">

        </dropdown-input>

    </div>
    
</x-talk::layout.single>