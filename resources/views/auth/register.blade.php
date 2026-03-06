@extends('app')

@section('content')
<script>
document.addEventListener('DOMContentLoaded', function () {
    showAuthModal();
    flipAuthCard(true);
});
</script>
@endsection
