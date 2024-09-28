@include('layouts.head')
@include('layouts.header')
<div id="sidebar-container">
    @include('layouts.sidebar')
</div>
@yield('content')
@include('layouts.footer')
@include('layouts.rightbar')
@yield('modals')
@include('layouts.scripts')