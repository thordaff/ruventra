<!-- Login Form -->
<div class="flip-card-front p-8 bg-white rounded-lg shadow relative flex flex-col items-center">
    <button onclick="closeAuthModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Login</h2>
    <form method="POST" action="{{ route('login') }}" class="w-full flex flex-col gap-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="email">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="current-password">
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition">Login</button>
    </form>
    <div class="mt-4 w-full flex flex-col items-center gap-2">
        <button type="button" onclick="flipAuthCard()" class="text-blue-600 hover:underline">Belum punya akun? Register</button>
        <a href="/reset-password" class="text-sm text-gray-500 hover:text-blue-600 hover:underline">Lupa password?</a>
    </div>
</div>
