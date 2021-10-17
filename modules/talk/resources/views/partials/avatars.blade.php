@if (count($users) == 1)
<x-ui.user.avatar :user="$users[0]" size="40" class="rounded-full inline-block" />
@elseif (count($users) == 2)
<x-ui.user.avatar :user="$users[0]" size="30" class="rounded-full inline-block absolute left-0 top-0 w-2/3 h-2/3" />
<x-ui.user.avatar :user="$users[1]" size="30" class="rounded-full inline-block absolute right-0 bottom-0 w-2/3 h-2/3" />
@elseif (count($users) == 3)
<x-ui.user.avatar :user="$users[0]" size="30" class="rounded-full inline-block absolute left-0 top-0 w-1/2" />
<x-ui.user.avatar :user="$users[1]" size="30" class="rounded-full inline-block absolute right-0 top-0 w-1/2" />
<x-ui.user.avatar :user="$users[2]" size="30" class="rounded-full inline-block absolute left-0 bottom-0 w-1/2" />
@elseif (count($users) >= 4)
<x-ui.user.avatar :user="$users[0]" size="30" class="rounded-full inline-block absolute left-0 top-0 w-1/2" />
<x-ui.user.avatar :user="$users[1]" size="30" class="rounded-full inline-block absolute right-0 top-0 w-1/2" />
<x-ui.user.avatar :user="$users[2]" size="30" class="rounded-full inline-block absolute left-0 bottom-0 w-1/2" />
<x-ui.user.avatar :user="$users[3]" size="30" class="rounded-full inline-block absolute right-0 bottom-0 w-1/2" />
@endif