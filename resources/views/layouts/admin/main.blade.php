<!DOCTYPE html>
<html lang="kr">
    @include('layouts.admin.head')
    <body>
    @include('layouts.admin.common.sidebar')
    <div class="wrapper d-flex flex-column min-vh-100">
        @include('layouts.admin.header')
        @yield('contents')
        @include('layouts.admin.footer')
    </div>
    @include('layouts.admin.common.scripts')
    @yield('additional-scripts')
    </body>
</html>
