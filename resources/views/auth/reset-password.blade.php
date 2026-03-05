@extends('app')

@section('content')
<div class="container" style="max-width:400px;margin:4rem auto;">
    <h2>Reset Password</h2>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div>
            <label>Email</label>
            <input type="email" name="email" required autofocus>
        </div>
        <button type="submit">Kirim Link Reset</button>
    </form>
    <div style="margin-top:1rem;">
        <a href="#" onclick="window.history.back()">Kembali ke Login</a>
    </div>
</div>
@endsection
