<!-- Modal Login/Register Flip Card -->
<div id="authModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/70"
    role="dialog" aria-modal="true" aria-labelledby="authModalTitle">
    <div id="authCard" class="flip-card w-full max-w-md relative">
        <span id="authModalTitle" class="sr-only">Login / Register</span>
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
        // Move focus into modal for accessibility
        const focusable = modal.querySelector('input, button, a[href], select, textarea, [tabindex]:not([tabindex="-1"])');
        if (focusable) focusable.focus();
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
        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                var modal = document.getElementById('authModal');
                if (!modal.classList.contains('hidden')) closeAuthModal();
            }
        });
    }
</script>
