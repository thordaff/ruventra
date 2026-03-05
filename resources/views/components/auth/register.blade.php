<!-- Register Form -->
<div class="flip-card-back p-8 bg-white rounded-lg shadow relative flex flex-col items-center">
    <button onclick="closeAuthModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Register</h2>
    <form method="POST" action="{{ route('register') }}" class="w-full flex flex-col gap-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="name">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="email">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="new-password">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="new-password">
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition">Register</button>
    </form>
    <div class="mt-4 w-full flex flex-col items-center gap-2">
        <button type="button" onclick="flipAuthCard(false)" class="text-blue-600 hover:underline">Sudah punya akun? Login</button>
    </div>
</div>
