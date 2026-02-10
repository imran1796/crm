
@if (auth()->check() && request()->route()->getName() != null)
    @include('layouts.navbars.auth')
@else
    @include('layouts.navbars.guest')
@endif