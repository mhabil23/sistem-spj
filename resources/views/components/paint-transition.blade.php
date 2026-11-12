{{-- Komponen Transisi Sapuan Cat (Paint Wipe) Zero-Flicker & Ultra-Smooth --}}
<script>
    (function () {
        try {
            if (sessionStorage.getItem('spj_paint_transition') === 'active') {
                document.documentElement.classList.add('paint-wipe-covered');
            }
        } catch (e) {}
    })();
</script>

<div id="paintWipeCurtain" class="paint-wipe-curtain" aria-hidden="true">
    <div class="paint-wipe-layer-accent"></div>
    <div class="paint-wipe-layer-main"></div>
    <div class="paint-wipe-badge">
        <img src="{{ asset('images/logo-bps-app.png') }}" alt="Logo BPS" onerror="this.style.display='none'">
        <span>BADAN PUSAT STATISTIK</span>
    </div>
</div>

