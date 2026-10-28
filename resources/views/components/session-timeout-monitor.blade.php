@auth
{{-- Auto Logout Monitor: Inactivity Timeout (1 Jam / 3600 Detik) --}}
<div id="sessionTimeoutModal" class="session-timeout-overlay" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="sessionTimeoutTitle">
    <div class="session-timeout-card">
        <div class="session-timeout-icon-box">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>

        <div class="session-timeout-content">
            <h3 id="sessionTimeoutTitle" class="session-timeout-title">Peringatan Tidak Ada Aktivitas</h3>
            <p class="session-timeout-desc">
                Anda tidak melakukan aktivitas selama hampir 1 jam. Demi keamanan data akun, sistem akan otomatis logout dalam:
            </p>

            <div class="session-timeout-countdown-box">
                <span id="sessionCountdownTimer" class="session-countdown-digit">02:00</span>
            </div>

            <p class="session-timeout-hint">
                Klik tombol <strong>"Lanjutkan Sesi"</strong> atau lakukan interaksi untuk tetap masuk ke akun Anda.
            </p>
        </div>

        <div class="session-timeout-actions">
            <button type="button" id="btnExtendSession" class="btn-session-stay">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>Lanjutkan Sesi</span>
            </button>
            <a href="{{ route('logout.idle') }}" class="btn-session-logout">
                <span>Keluar Sekarang</span>
            </a>
        </div>
    </div>
</div>

<style>
.session-timeout-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 9999999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    box-sizing: border-box;
    animation: sessionFadeIn 0.25s ease-out forwards;
}

.session-timeout-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    max-width: 460px;
    width: 100%;
    padding: 28px 24px;
    box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.05);
    text-align: center;
    box-sizing: border-box;
    animation: sessionPopUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

html.dark-theme .session-timeout-card {
    background: #1e293b;
    border-color: #334155;
    color: #f8fafc;
}

.session-timeout-icon-box {
    width: 64px;
    height: 64px;
    background: #fef3c7;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 18px auto;
    box-shadow: 0 0 0 8px #fffbeb;
}

html.dark-theme .session-timeout-icon-box {
    background: rgba(217, 119, 6, 0.2);
    box-shadow: 0 0 0 8px rgba(217, 119, 6, 0.1);
}

.session-timeout-title {
    font-size: 19px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 8px 0;
    letter-spacing: -0.02em;
}

html.dark-theme .session-timeout-title {
    color: #f8fafc;
}

.session-timeout-desc {
    font-size: 13.5px;
    color: #475569;
    line-height: 1.55;
    margin: 0 0 16px 0;
}

html.dark-theme .session-timeout-desc {
    color: #94a3b8;
}

.session-timeout-countdown-box {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 10px 24px;
    margin-bottom: 14px;
}

html.dark-theme .session-timeout-countdown-box {
    background: #0f172a;
    border-color: #475569;
}

.session-countdown-digit {
    font-size: 32px;
    font-weight: 800;
    color: #d97706;
    letter-spacing: 0.05em;
    font-variant-numeric: tabular-nums;
    font-family: monospace, sans-serif;
}

.session-timeout-hint {
    font-size: 12px;
    color: #64748b;
    margin: 0 0 24px 0;
    line-height: 1.5;
}

html.dark-theme .session-timeout-hint {
    color: #94a3b8;
}

.session-timeout-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-session-stay {
    flex: 1;
    min-width: 160px;
    background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    color: #ffffff;
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
    transition: all 0.2s ease;
}

.btn-session-stay:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(2, 132, 199, 0.4);
}

.btn-session-logout {
    flex: 1;
    min-width: 140px;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #cbd5e1;
    padding: 12px 18px;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.btn-session-logout:hover {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fca5a5;
}

html.dark-theme .btn-session-logout {
    background: #334155;
    color: #cbd5e1;
    border-color: #475569;
}

html.dark-theme .btn-session-logout:hover {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171;
    border-color: #ef4444;
}

@keyframes sessionFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes sessionPopUp {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>

<script>
(function() {
    // Konfigurasi Idle Timeout (1 jam = 3600 detik)
    const MAX_IDLE_SECONDS = 3600; // 60 menit
    const WARNING_SECONDS = 120;   // Munculkan peringatan 2 menit sebelum logout (detik 3480)
    const LOGOUT_URL = "{{ route('logout.idle') }}";
    const PING_URL = "{{ route('session.ping') }}";

    let lastActivity = Date.now();
    let warningShown = false;
    let countdownInterval = null;
    let lastPing = Date.now();

    const modal = document.getElementById('sessionTimeoutModal');
    const timerElem = document.getElementById('sessionCountdownTimer');
    const stayBtn = document.getElementById('btnExtendSession');

    // Reset aktivitas pengguna saat ada interaksi
    function recordActivity() {
        lastActivity = Date.now();

        // Jika modal sedang terbuka dan user melakukan interaksi (misal klik lanjutkan)
        if (warningShown) {
            extendSession();
        }

        // Batasi keepalive ping maksimal setiap 5 menit sekali saat ada aktivitas reguler
        if (Date.now() - lastPing > 300000) {
            pingServer();
        }
    }

    // Perpanjang sesi & sembunyikan modal
    function extendSession() {
        lastActivity = Date.now();
        warningShown = false;
        if (modal) modal.style.display = 'none';
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
        pingServer();
    }

    // Ping server untuk memperbarui sesi PHP
    function pingServer() {
        lastPing = Date.now();
        fetch(PING_URL, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        }).catch(() => {});
    }

    // Format detik ke format MM:SS
    function formatTime(seconds) {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    }

    // Eksekusi logout otomatis
    function triggerAutoLogout() {
        if (countdownInterval) clearInterval(countdownInterval);
        window.location.href = LOGOUT_URL;
    }

    // Timer pengecekan idle setiap 1 detik
    setInterval(function() {
        const idleSeconds = Math.floor((Date.now() - lastActivity) / 1000);
        const remainingSeconds = MAX_IDLE_SECONDS - idleSeconds;

        if (remainingSeconds <= 0) {
            triggerAutoLogout();
            return;
        }

        // Tampilkan modal peringatan jika tersisa <= 120 detik (58 menit tidak ada aktivitas)
        if (remainingSeconds <= WARNING_SECONDS && !warningShown) {
            warningShown = true;
            if (modal) modal.style.display = 'flex';
            if (timerElem) timerElem.textContent = formatTime(remainingSeconds);

            if (!countdownInterval) {
                countdownInterval = setInterval(function() {
                    const currentIdle = Math.floor((Date.now() - lastActivity) / 1000);
                    const left = MAX_IDLE_SECONDS - currentIdle;

                    if (left <= 0) {
                        triggerAutoLogout();
                    } else if (timerElem) {
                        timerElem.textContent = formatTime(left);
                    }
                }, 1000);
            }
        }
    }, 1000);

    // Event listener untuk mendeteksi interaksi pengguna
    const events = ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click'];
    let eventThrottle = null;

    events.forEach(function(evt) {
        window.addEventListener(evt, function() {
            if (!eventThrottle) {
                eventThrottle = setTimeout(function() {
                    recordActivity();
                    eventThrottle = null;
                }, 1000);
            }
        }, { passive: true });
    });

    // Pengecekan saat tab kembali aktif (misal laptop baru dibuka kembali dari mode sleep)
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            const idleSeconds = Math.floor((Date.now() - lastActivity) / 1000);
            if (idleSeconds >= MAX_IDLE_SECONDS) {
                triggerAutoLogout();
            } else {
                recordActivity();
            }
        }
    });

    if (stayBtn) {
        stayBtn.addEventListener('click', function(e) {
            e.preventDefault();
            extendSession();
        });
    }
})();
</script>
@endauth
