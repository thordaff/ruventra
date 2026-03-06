@extends('app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Dashboard</h1>
    <div class="bg-white rounded-lg shadow p-6">
        @if(auth()->user()->roles->contains('name', 'developer'))
            <p class="text-gray-700">Selamat datang di dashboard developer. Anda dapat mengakses fitur pengembangan, monitoring, dan tools internal.</p>
        @endif
        @if(auth()->user()->roles->contains('name', 'superAdmin'))
            <p class="text-gray-700">Selamat datang di dashboard superAdmin. Anda dapat mengelola seluruh sistem, user, dan event perusahaan.</p>
        @endif
        {{-- Tambahkan fitur lain sesuai role di sini --}}
    </div>
</div>
@endsection
