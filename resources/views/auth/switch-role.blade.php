@if(Auth::check())
    <div>
        <span>Active Role: {{ session('active_role', Auth::user()->roles->first()->name ?? '-') }}</span>
        <form method="GET" action="{{ route('switch.role', 'admin') }}" style="display:inline">
            <button type="submit">Switch to Admin</button>
        </form>
        <form method="GET" action="{{ route('switch.role', 'customer') }}" style="display:inline">
            <button type="submit">Switch to Customer</button>
        </form>
    </div>
@endif
