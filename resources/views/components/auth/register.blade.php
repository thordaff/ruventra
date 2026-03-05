<!-- Register Form -->
<div class="flip-card-back">
    <button onclick="closeAuthModal()" style="position:absolute;top:10px;right:10px;">&times;</button>
    <h2>Register</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <label>Name</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required>
        </div>
        <button type="submit">Register</button>
    </form>
    <div style="margin-top:1rem;">
        <button type="button" onclick="flipAuthCard(false)">Sudah punya akun? Login</button>
    </div>
</div>
