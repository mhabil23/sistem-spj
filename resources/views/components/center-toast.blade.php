@if(session('success') || session('error'))
<div id="centerToastWrapper" class="center-toast-overlay">
    <div id="centerToast" class="center-toast-card center-toast-{{ session('success') ? 'success' : 'error' }}" onclick="dismissToast()" role="alert" title="Klik untuk menutup pesan ini">
        <div class="center-toast-icon">
            @if(session('success'))
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            @endif
        </div>
        <div class="center-toast-body">
            <div class="center-toast-title">{{ session('success') ? 'Berhasil' : 'Pemberitahuan' }}</div>
            <div class="center-toast-message">{{ session('success') ?? session('error') }}</div>
        </div>
        <button type="button" class="center-toast-close" onclick="dismissToast(); event.stopPropagation();" aria-label="Tutup notifikasi">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <div class="center-toast-progress"></div>
    </div>
</div>

<style>
.center-toast-overlay {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 999999;
    pointer-events: auto;
}

.center-toast-card {
    min-width: 320px;
    max-width: 520px;
    width: 90vw;
    background-color: var(--dash-card-bg, #ffffff);
    border: 1px solid var(--dash-border, #e2e8f0);
    border-radius: 16px;
    padding: 18px 22px;
    box-shadow: 0 20px 45px rgba(15, 35, 45, 0.28), 0 4px 14px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 15px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    animation: toastPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    transition: transform 0.2s ease, opacity 0.2s ease, box-shadow 0.2s ease;
    user-select: none;
    font-family: inherit;
    box-sizing: border-box;
}

.center-toast-card:hover {
    transform: scale(1.02);
    box-shadow: 0 24px 50px rgba(15, 35, 45, 0.35);
}

.center-toast-card.hide-toast {
    animation: toastPopOut 0.2s cubic-bezier(0.4, 0, 1, 1) forwards !important;
}

@keyframes toastPopIn {
    0% {
        opacity: 0;
        transform: scale(0.85);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes toastPopOut {
    0% {
        opacity: 1;
        transform: scale(1);
    }
    100% {
        opacity: 0;
        transform: scale(0.9);
    }
}

.center-toast-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.center-toast-success .center-toast-icon {
    background: #ecfdf5;
    color: #10b981;
}

html.dark-theme .center-toast-success .center-toast-icon {
    background: rgba(16, 185, 129, 0.2);
    color: #34d399;
}

.center-toast-error .center-toast-icon {
    background: #fef2f2;
    color: #ef4444;
}

html.dark-theme .center-toast-error .center-toast-icon {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171;
}

.center-toast-body {
    flex: 1;
    min-width: 0;
}

.center-toast-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--dash-text-dark, #0f172a);
    margin-bottom: 3px;
}

.center-toast-message {
    font-size: 13px;
    line-height: 1.45;
    color: var(--dash-text-muted, #475569);
    word-break: break-word;
}

.center-toast-close {
    background: none;
    border: none;
    color: var(--dash-text-muted, #94a3b8);
    cursor: pointer;
    padding: 6px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
    flex-shrink: 0;
}

.center-toast-close:hover {
    background: var(--dash-input-bg, #f1f5f9);
    color: var(--dash-text-dark, #0f172a);
}

.center-toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    width: 100%;
    background: linear-gradient(90deg, #10b981, #0284c7);
    animation: toastProgress 2s linear forwards;
}

.center-toast-error .center-toast-progress {
    background: linear-gradient(90deg, #ef4444, #f97316);
}

@keyframes toastProgress {
    0% {
        width: 100%;
    }
    100% {
        width: 0%;
    }
}
</style>

<script>
    function dismissToast() {
        const toast = document.getElementById('centerToast');
        if (toast && !toast.classList.contains('hide-toast')) {
            toast.classList.add('hide-toast');
            setTimeout(() => {
                const wrapper = document.getElementById('centerToastWrapper');
                if (wrapper) wrapper.remove();
            }, 220);
        }
    }

    (function() {
        const toast = document.getElementById('centerToast');
        if (!toast) return;

        let dismissTimer = setTimeout(dismissToast, 2000);

        toast.addEventListener('mouseenter', () => {
            clearTimeout(dismissTimer);
            const progress = toast.querySelector('.center-toast-progress');
            if (progress) progress.style.animationPlayState = 'paused';
        });

        toast.addEventListener('mouseleave', () => {
            const progress = toast.querySelector('.center-toast-progress');
            if (progress) progress.style.animationPlayState = 'running';
            dismissTimer = setTimeout(dismissToast, 1200);
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                dismissToast();
            }
        });
    })();
</script>
@endif
