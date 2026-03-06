@if(auth()->check())
    <div>
        <span>Active Role: {{ session('active_role', auth()->user()->roles->first()->name ?? '-') }}</span>
        <form method="POST" action="{{ route('switch.role', 'admin') }}" style="display:inline">
            @csrf
            <button type="submit">Switch to Admin</button>
        </form>
        <form method="POST" action="{{ route('switch.role', 'customer') }}" style="display:inline">
            @csrf
            <button type="submit">Switch to Customer</button>
        </form>
    </div>
@endif
