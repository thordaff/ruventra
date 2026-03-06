<nav class="bg-white px-8 py-4 flex justify-between items-center shadow">
    <div>
        <a href="/" class="font-bold text-lg text-gray-800">Ruventra</a>
    </div>
    <div>
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded transition">Logout</button>
            </form>
        @else
            <button onclick="showAuthModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded transition">Login / Register</button>
        @endauth
    </div>
</nav>
