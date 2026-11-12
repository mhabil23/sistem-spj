/**
 * SISTEM SPJ BPS - APPLICATION CORE JAVASCRIPT
 * Page Transitions: Smooth Fade & Slide to Left / Right
 * Menangani transisi mulus dan responsif antar Beranda, Login, dan Dashboard
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Pulihkan state transisi jika halaman diambil dari browser cache / tombol Back
    window.addEventListener('pageshow', function (event) {
        document.body.classList.remove('page-transition-exit-left', 'page-transition-exit-right');
    });

    /**
     * Navigasi berpindah halaman dengan transisi halus
     * @param {string} url - Target URL tujuan
     * @param {'left'|'right'} direction - Arah memudar ('left' = maju ke login/dashboard, 'right' = kembali ke beranda)
     */
    function navigateWithTransition(url, direction = 'left') {
        const exitClass = direction === 'left' ? 'page-transition-exit-left' : 'page-transition-exit-right';
        document.body.classList.add(exitClass);

        setTimeout(function () {
            window.location.href = url;
        }, 220);
    }

    // 2. Tangani klik pada tautan navigasi
    document.addEventListener('click', function (e) {
        // Abaikan jika modifier key ditekan (Ctrl/Cmd untuk buka tab baru, klik kanan, dll)
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

        // A. Dari Beranda menuju Login (Maju: Memudar bergeser ke kiri)
        if (link.classList.contains('login-btn') || link.classList.contains('mobile-login-btn') || href.endsWith('/login') || href.includes('/login?')) {
            e.preventDefault();
            navigateWithTransition(link.href, 'left');
            return;
        }

        // B. Dari Beranda menuju Dashboard (Maju: Memudar bergeser ke kiri)
        if (link.classList.contains('dashboard-badge') || (href.includes('/dashboard') && (window.location.pathname === '/' || window.location.pathname === ''))) {
            e.preventDefault();
            navigateWithTransition(link.href, 'left');
            return;
        }

        // C. Dari Login kembali ke Beranda (Mundur: Memudar bergeser ke kanan)
        if (link.classList.contains('link-back-home') || link.classList.contains('brand-link') || link.classList.contains('btn-back-home')) {
            e.preventDefault();
            navigateWithTransition(link.href, 'right');
            return;
        }
    });

    // 3. Tangani Submit Form Login (Transisi memudar ke kiri menuju Dashboard)
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            // Jika form valid dan siap dikirim
            if (loginForm.checkValidity && loginForm.checkValidity()) {
                document.body.classList.add('page-transition-exit-left');
                const submitBtn = loginForm.querySelector('.btn-submit-login');
                if (submitBtn) {
                    submitBtn.classList.add('loading');
                }
            }
        });
    }
});
