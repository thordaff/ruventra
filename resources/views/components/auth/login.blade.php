<!-- Login Form -->
<div class="flip-card-front">
    <button onclick="closeAuthModal()" style="position:absolute;top:10px;right:10px;">&times;</button>
    <h2>Login</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <div style="margin-top:1rem;">
        <button type="button" onclick="flipAuthCard()">Belum punya akun? Register</button>
    </div>
    <div style="margin-top:0.5rem;">
        <a href="/reset-password">Lupa password?</a>
    </div>
</div>
