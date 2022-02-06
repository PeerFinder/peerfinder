<a href="{{ route($route) }}" class="block px-4 py-2 hover:text-pf-midblue {{ Route::currentRouteNamed($route) ? 'border-b-2 border-b-pf-midblue font-semibold' : '' }}">
    {{ $slot }}
</a>