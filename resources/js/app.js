/**
 * SISTEM SPJ BPS - APPLICATION CORE JAVASCRIPT
 * Paint Wipe Smooth Page Transition (Zero-Flicker & Ultra-Silky)
 * Menghadirkan transisi sapuan cat yang pelan, tenang, dan mulus tanpa kedip
 */

(function () {
    // 1. Dapatkan atau buat elemen Paint Wipe Curtain di DOM
    function getCurtain() {
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

    // 2. Tangani Sapuan Cat Keluar ke Kiri (Reveal Halaman Baru)
    function startPaintReveal() {
        const isCovered = document.documentElement.classList.contains('paint-wipe-covered');
        const hasTransitionFlag = sessionStorage.getItem('spj_paint_transition') === 'active';

        if (isCovered || hasTransitionFlag) {
            sessionStorage.removeItem('spj_paint_transition');
            const curtain = getCurtain();

            // Posisikan tirai aktif menutup layar
            curtain.classList.add('active', 'wiping-in');
            document.documentElement.classList.remove('paint-wipe-covered');

            // Beri jeda tenang sejenak agar transisi tidak terburu-buru, lalu sapukan keluar ke kiri
            setTimeout(function () {
                curtain.classList.remove('wiping-in');
                curtain.classList.add('wiping-out');

                // Biarkan tirai bergeser sepenuhnya ke luar batas layar kiri (-135%)
                setTimeout(function () {
                    curtain.classList.remove('active', 'wiping-out');
                }, 900);
            }, 100);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startPaintReveal);
    } else {
        startPaintReveal();
    }

    // Pulihkan layar jika halaman dibuka melalui tombol Back/Forward browser (BFCache)
    window.addEventListener('pageshow', function (event) {
        document.documentElement.classList.remove('paint-wipe-covered');
        const curtain = document.getElementById('paintWipeCurtain');
        if (curtain) {
            curtain.classList.remove('active', 'wiping-in', 'wiping-out');
        }
    });

    /**
     * Memicu sapuan cat halus dari kanan ke kiri secara pelan dan tenang
     * @param {string} url - Target URL tujuan
     */
    function triggerPaintWipe(url) {
        const curtain = getCurtain();
        curtain.classList.remove('wiping-out');
        curtain.classList.add('active');

        // Force reflow agar animasi CSS terpicu bersih
        void curtain.offsetWidth;

        curtain.classList.add('wiping-in');

        // Set flag agar halaman tujuan tertutup penuh sejak frame pertama
        try {
            sessionStorage.setItem('spj_paint_transition', 'active');
        } catch (e) {}

        // Navigasi saat layar sudah tertutup penuh oleh lapisan cat tebal
        setTimeout(function () {
            window.location.href = url;
        }, 800);
    }

    // 3. Tangani Klik Navigasi Antar Halaman
    document.addEventListener('click', function (e) {
        // Abaikan jika modifier key ditekan (Ctrl, Cmd, Shift, klik tengah mouse)
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

    // 4. Tangani Submit Form Login dengan Transisi Sapuan Cat ke Dashboard
    document.addEventListener('DOMContentLoaded', function () {
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function (e) {
                e.preventDefault();

                // Validasi form HTML5
                if (loginForm.checkValidity && !loginForm.checkValidity()) {
                    if (typeof loginForm.reportValidity === 'function') {
                        loginForm.reportValidity();
                    }
                    return;
                }

                const submitBtn = loginForm.querySelector('.btn-submit-login');
                const ajaxAlert = document.getElementById('loginAjaxAlert');
                const ajaxErrMsg = document.getElementById('loginAjaxErrorMessage');

                // Sembunyikan alert error lama jika ada
                if (ajaxAlert) ajaxAlert.style.display = 'none';

                // Ubah status tombol jadi memverifikasi
                if (submitBtn) {
                    submitBtn.classList.add('loading');
                    submitBtn.style.pointerEvents = 'none';
                    submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spin-icon"></i> <span>Memverifikasi Akun...</span>';
                }

                const formData = new FormData(loginForm);

                fetch(loginForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, status: response.status, data: data };
                    });
                })
                .then(function (result) {
                    if (result.ok && result.data && result.data.status === 'success') {
                        // Login Berhasil: Ubah label tombol lalu picu sapuan cat ke Dashboard
                        if (submitBtn) {
                            submitBtn.innerHTML = '<i class="bi bi-check2-circle"></i> <span>Berhasil! Mengalihkan...</span>';
                        }
                        const targetDashboard = result.data.redirect_url || '/';
                        triggerPaintWipe(targetDashboard);
                    } else {
                        // Kredensial Salah / Error Validasi
                        if (submitBtn) {
                            submitBtn.classList.remove('loading');
                            submitBtn.style.pointerEvents = '';
                            submitBtn.innerHTML = '<span>Masuk ke Sistem</span> <i class="bi bi-arrow-right"></i>';
                        }
                        const msg = (result.data && result.data.message) ? result.data.message : 'Email atau kata sandi yang Anda masukkan tidak sesuai.';
                        if (ajaxAlert && ajaxErrMsg) {
                            ajaxErrMsg.textContent = msg;
                            ajaxAlert.style.display = 'flex';
                        }
                        const passInput = document.getElementById('password');
                        if (passInput) {
                            passInput.value = '';
                            passInput.focus();
                        }
                    }
                })
                .catch(function (error) {
                    // Fallback jika terjadi kegagalan jaringan: submit konvensional
                    console.error('AJAX login error:', error);
                    loginForm.submit();
                });
            });
        }
    });
})();
