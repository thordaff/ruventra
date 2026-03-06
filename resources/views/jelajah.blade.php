@extends('app')

@section('content')
<div class="w-full max-w-5xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Jelajah Event</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($events as $event)
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition cursor-pointer group" onclick="handleEventClick({{ $event->id }})">
            <img src="{{ $event->image_url ?? 'https://placehold.co/400x200?text=Event' }}" alt="{{ $event->name }}" class="rounded-t-lg w-full h-40 object-cover">
            <div class="p-4">
                <h2 class="text-xl font-semibold mb-2 group-hover:text-blue-600">{{ $event->name }}</h2>
                <p class="text-gray-500 mb-2">{{ $event->date }}</p>
                <p class="text-gray-700">{{ Str::limit($event->description, 80) }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
<script>
function handleEventClick(eventId) {
    @if(Auth::check())
        window.location.href = '/event/' + eventId;
    @else
        showAuthModal();
    @endif
}
</script>
@endsection
