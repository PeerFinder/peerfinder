@props(['user', 'style' => 'full'])

@if ($user->is_supporter || $user->is_verified_person)
    @if ($style == 'full')
        <div class="flex flex-wrap items-center my-4">
            @if ($user->is_verified_person)
            <div class="py-1 px-2 rounded-md bg-gray-50 mr-1 mb-1"><x-ui.icon name="badge-check" class="text-pf-midblue" viewBox="0 2 20 20" /> @lang('profile/user.award_verified', ['name' => config('app.name')])</div>
            @endif
            @if ($user->is_supporter)
            <div class="py-1 px-2 rounded-md bg-gray-50 mr-1 mb-1"><x-ui.icon name="heart" class="text-pf-darkorange" viewBox="0 2 20 20" /> @lang('profile/user.award_supporter', ['name' => config('app.name')])</div>
            @endif
        </div>
    @elseif ($style == 'inline')
        <div class="inline-flex">
            @if ($user->is_verified_person)
            <div title="{{ __('profile/user.award_verified') }}"><x-ui.icon name="badge-check" class="text-pf-midblue pb-0" viewBox="0 1 20 20" /></div>
            @endif
            @if ($user->is_supporter)
            <div title="{{ __('profile/user.award_supporter', ['name' => config('app.name')]) }}"><x-ui.icon name="heart" class="text-pf-darkorange pb-0" viewBox="0 1 20 20" /></div>
            @endif
        </div>
    @endif
@endif