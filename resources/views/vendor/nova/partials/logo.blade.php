<img src="{{ asset('/images/peerfinder_logo.png') }}" srcset="{{ asset('/images/peerfinder_logo@2x.png') }} 2x" style="width: 1.5em;" />
<div class="ml-3">{{ config('app.name') }} <span class="font-bold block">Admin</span></div>
@if (env('APP_ENV') == 'production')
<div class="uppercase ml-2 rounded-sm text-sm p-1" style="background-color: red;">Prod</div>
@endif