/**
 * SISTEM SPJ BPS - APPLICATION CORE JAVASCRIPT
 * Paint Wipe Smooth Page Transition (Sapuan Cat Halus dari Kanan ke Kiri)
 * Menghadirkan transisi artistik dan tenang saat berpindah antar Beranda, Login, & Dashboard
 */

(function () {
    // 1. Buat elemen Paint Wipe Curtain secara dinamis jika belum ada di DOM
    function ensureCurtainElement() {
        let curtain = document.getElementById('paintWipeCurtain');
        if (!curtain) {
            curtain = document.createElement('div');
            curtain.id = 'paintWipeCurtain';
            curtain.className = 'paint-wipe-curtain';
            curtain.setAttribute('aria-hidden', 'true');
            curtain.innerHTML = `
                <div class="paint-wipe-layer-accent"></div>
                <div class="paint-wipe-layer-main"></div>
                <div class="paint-wipe-badge">
                    <img src="/images/logo-bps-app.png" alt="Logo BPS" onerror="this.style.display='none'">
                    <span>BADAN PUSAT STATISTIK</span>
                </div>
            `;
            document.body.appendChild(curtain);
        }
        return curtain;
    }

    // 2. Tangani Reveal (Sapuan cat meluncur keluar ke arah kiri) saat halaman baru selesai dimuat
    function handleInitialReveal() {
        if (sessionStorage.getItem('spj_paint_transition') === 'active') {
            sessionStorage.removeItem('spj_paint_transition');
            const curtain = ensureCurtainElement();

            // Posisikan curtain langsung menutup layar saat halaman pertama kali render
            curtain.classList.remove('wiping-out');
            curtain.classList.add('active', 'wiping-in');

            // Jalankan sapuan keluar ke arah kiri setelah jeda mikro
            requestAnimationFrame(function () {
                setTimeout(function () {
                    curtain.classList.remove('wiping-in');
                    curtain.classList.add('wiping-out');

                    // Nonaktifkan curtain setelah animasi sapuan selesai
                    setTimeout(function () {
                        curtain.classList.remove('active', 'wiping-out');
                    }, 650);
                }, 80);
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', handleInitialReveal);
    } else {
        handleInitialReveal();
    }

    // Pulihkan layar jika halaman dibuka melalui tombol Back/Forward browser (BFCache)
    window.addEventListener('pageshow', function (event) {
        const curtain = document.getElementById('paintWipeCurtain');
        if (curtain) {
            curtain.classList.remove('active', 'wiping-in', 'wiping-out');
        }
    });

    /**
     * Memicu sapuan cat halus dari kanan ke kiri
     * @param {string} url - Target URL tujuan
     */
    function triggerPaintWipe(url) {
        const curtain = ensureCurtainElement();
        curtain.classList.remove('wiping-out');
        curtain.classList.add('active');

        // Force reflow agar animasi CSS terpicu bersih
        void curtain.offsetWidth;

        curtain.classList.add('wiping-in');

        // Tandai flag agar halaman berikutnya membuka dengan sapuan keluar ke kiri
        sessionStorage.setItem('spj_paint_transition', 'active');

        // Berpindah halaman tepat saat layar telah tertutup penuh oleh lapisan cat
        setTimeout(function () {
            window.location.href = url;
        }, 560);
    }

    // 3. Tangani Klik Navigasi Antar Halaman
    document.addEventListener('click', function (e) {
        // Abaikan jika ada tombol pengubah aktif (Ctrl, Cmd, Shift, klik tengah mouse)
        if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
            return;
        }

        const link = e.target.closest('a');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) {
            return;
        }

        if (link.getAttribute('target') === '_blank' || link.hasAttribute('download')) {
            return;
        }

        // A. Dari Beranda menuju Login
        if (link.classList.contains('login-btn') || link.classList.contains('mobile-login-btn') || href.endsWith('/login') || href.includes('/login?')) {
            e.preventDefault();
            triggerPaintWipe(link.href);
            return;
        }

        // B. Dari Beranda menuju Dashboard
        if (link.classList.contains('dashboard-badge') || (href.includes('/dashboard') && (window.location.pathname === '/' || window.location.pathname === ''))) {
            e.preventDefault();
            triggerPaintWipe(link.href);
            return;
        }

        // C. Dari Halaman Login kembali ke Beranda
        if (link.classList.contains('link-back-home') || link.classList.contains('brand-link') || link.classList.contains('btn-back-home')) {
            e.preventDefault();
            triggerPaintWipe(link.href);
            return;
        }
    });

    // 4. Tangani Submit Form Login (Sapuan cat lembut saat masuk ke Dashboard)
    document.addEventListener('DOMContentLoaded', function () {
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function (e) {
                if (loginForm.checkValidity && loginForm.checkValidity()) {
                    sessionStorage.setItem('spj_paint_transition', 'active');
                    const curtain = ensureCurtainElement();
                    curtain.classList.remove('wiping-out');
                    curtain.classList.add('active');
                    void curtain.offsetWidth;
                    curtain.classList.add('wiping-in');

                    const submitBtn = loginForm.querySelector('.btn-submit-login');
                    if (submitBtn) {
                        submitBtn.classList.add('loading');
                        submitBtn.style.pointerEvents = 'none';
                    }
                }
            });
        }
    });
})();
