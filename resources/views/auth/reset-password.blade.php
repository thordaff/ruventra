@extends('app')

@section('content')
<div class="mx-auto max-w-md bg-white rounded-lg shadow p-8 mt-12">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Reset Password</h2>
    <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" required autofocus class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition">Kirim Link Reset</button>
    </form>
    <div class="mt-4 text-center">
        <a href="#" onclick="window.history.back()" class="text-blue-600 hover:underline">Kembali ke Login</a>
    </div>
</div>
@endsection
