<!-- Modal Login/Register Flip Card -->
<div id="authModal" style="display:none;position:fixed;z-index:2000;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);">
    <div id="authCard" class="flip-card" style="background:none;margin:10vh auto;max-width:400px;position:relative;">
        <div id="authCardInner" class="flip-card-inner">
            <!-- Login Side -->
            @include('components.auth.login')
            <!-- Register Side -->
            @include('components.auth.register')
        </div>
    </div>
</div>
<script>
function showAuthModal() {
    document.getElementById('authModal').style.display = 'block';
    document.getElementById('authCardInner').style.transform = 'rotateY(0deg)';
}
function closeAuthModal() {
    document.getElementById('authModal').style.display = 'none';
}
function flipAuthCard(toRegister = true) {
    document.getElementById('authCardInner').style.transform = toRegister ? 'rotateY(180deg)' : 'rotateY(0deg)';
}
// Close modal on outside click
if (typeof window !== 'undefined') {
    document.addEventListener('click', function(e) {
        var modal = document.getElementById('authModal');
        if (e.target === modal) closeAuthModal();
    });
}
</script>
