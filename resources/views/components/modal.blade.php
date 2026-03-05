<!-- Modal Login/Register Flip Card -->
<div id="authModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/70">
    <div id="authCard" class="flip-card w-full max-w-md relative">
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
        const modal = document.getElementById('authModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('authCardInner').style.transform = 'rotateY(0deg)';
    }
    function closeAuthModal() {
        const modal = document.getElementById('authModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

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
