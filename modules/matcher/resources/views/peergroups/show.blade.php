<x-matcher::layout.single :pg="$pg" :infocards="$infocards">
    @include('matcher::partials.pending-list')
    @include('matcher::partials.membership-menu')
    @include('matcher::partials.group-description')
    @include('matcher::partials.conversations')
</x-matcher::layout.single>