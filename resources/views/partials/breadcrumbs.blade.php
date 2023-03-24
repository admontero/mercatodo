<ol class="breadcrumb">
    @if (request()->routeIs('admin.dashboard'))
        <li class="breadcrumb-item active">
            Dashboard
        </li>
    @else
        <li class="breadcrumb-item">
            <a href="/admin/dashboard">
                Dashboard
            </a>
        </li>
    @endif

    @yield('breadcrumb')
</ol>
