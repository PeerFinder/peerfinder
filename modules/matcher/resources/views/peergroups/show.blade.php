<x-matcher::layout.single :pg="$pg">
    <x-matcher::peergroup.pending-list :pending="$pending" :pg="$pg" />

    <x-matcher::peergroup.membership-menu :pg="$pg" />
    
    <x-matcher::peergroup.group-description :pg="$pg" />

    <x-matcher::peergroup.conversations :pg="$pg" :conversations="$conversations" />
</x-matcher::layout.single>