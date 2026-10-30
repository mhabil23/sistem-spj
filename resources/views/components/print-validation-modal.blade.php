<!-- MODAL POP-UP VALIDASI CETAK CHECKLIST -->
<div id="printValidationModal" class="pvm-overlay" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="pvmTitle">
    <div class="pvm-card">
        <!-- Close Button -->
        <button type="button" class="pvm-close-btn" onclick="closePrintValidationModal()" aria-label="Tutup Modal" title="Tutup">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <!-- Icon Badge -->
        <div class="pvm-icon-wrapper">
            <div class="pvm-icon-pulse"></div>
            <div class="pvm-icon-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
            </div>
        </div>

        <!-- Content -->
        <div class="pvm-body">
            <h3 id="pvmTitle" class="pvm-title">Lembar Checklist Belum Dicetak!</h3>
            <p class="pvm-description">
                SPJ <strong>tidak dapat diajukan</strong> jika Anda belum menekan tombol <strong>Print</strong> sampai lembar checklist fisik tercetak.
            </p>

            <div class="pvm-info-box">
                <div class="pvm-info-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                </div>
                <div class="pvm-info-text">
                    Lembar checklist ini wajib dilampirkan bersama <strong>dokumen fisik SPJ</strong> saat diserahkan ke meja verifikator <strong>Subbagian Umum</strong>.
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="pvm-actions">
            <button type="button" class="pvm-btn pvm-btn-primary" onclick="proceedPrintFromModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Cetak Lembar Checklist Sekarang</span>
            </button>
            <button type="button" class="pvm-btn pvm-btn-secondary" onclick="closePrintValidationModal()">
                Tutup & Nanti Saja
            </button>
        </div>
    </div>
</div>

<style>
.pvm-overlay {
    position: fixed;
    inset: 0;
    z-index: 999999;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    box-sizing: border-box;
    animation: pvmFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.pvm-card {
    background: var(--dash-card-bg, #ffffff);
    border: 1px solid var(--dash-border, #e2e8f0);
    border-radius: 20px;
    width: 100%;
    max-width: 480px;
    padding: 32px 28px 26px;
    box-shadow: 0 25px 60px -12px rgba(15, 23, 42, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.1);
    position: relative;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    animation: pvmScaleUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    box-sizing: border-box;
    font-family: inherit;
}

html.dark-theme .pvm-card {
    background: #1e293b;
    border-color: #334155;
    box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.65), 0 0 0 1px rgba(255, 255, 255, 0.05);
}

.pvm-close-btn {
    position: absolute;
    top: 16px;
    right: 16px;
    background: transparent;
    border: none;
    color: var(--dash-text-muted, #94a3b8);
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
}

.pvm-close-btn:hover {
    background: var(--dash-input-bg, #f1f5f9);
    color: var(--dash-text-dark, #0f172a);
}

html.dark-theme .pvm-close-btn:hover {
    background: #334155;
    color: #f8fafc;
}

.pvm-icon-wrapper {
    position: relative;
    width: 72px;
    height: 72px;
    margin-bottom: 20px;
}

.pvm-icon-pulse {
    position: absolute;
    inset: -6px;
    border-radius: 50%;
    background: #fef3c7;
    opacity: 0.6;
    animation: pvmPulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

html.dark-theme .pvm-icon-pulse {
    background: rgba(245, 158, 11, 0.25);
}

.pvm-icon-box {
    position: relative;
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
    color: #d97706;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fde68a;
    box-shadow: 0 8px 20px rgba(217, 119, 6, 0.2);
}

html.dark-theme .pvm-icon-box {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.25) 0%, rgba(234, 88, 12, 0.3) 100%);
    border-color: rgba(245, 158, 11, 0.4);
    color: #fbbf24;
}

.pvm-body {
    width: 100%;
    margin-bottom: 24px;
}

.pvm-title {
    font-size: 19px;
    font-weight: 800;
    color: var(--dash-text-dark, #0f172a);
    margin: 0 0 10px 0;
    letter-spacing: -0.02em;
}

html.dark-theme .pvm-title {
    color: #f8fafc;
}

.pvm-description {
    font-size: 14px;
    line-height: 1.55;
    color: var(--dash-text-muted, #475569);
    margin: 0 0 18px 0;
}

html.dark-theme .pvm-description {
    color: #cbd5e1;
}

.pvm-description strong {
    color: var(--dash-text-dark, #0f172a);
}

html.dark-theme .pvm-description strong {
    color: #f8fafc;
}

.pvm-info-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    text-align: left;
}

html.dark-theme .pvm-info-box {
    background: #0f172a;
    border-color: #334155;
}

.pvm-info-icon {
    color: #0284c7;
    flex-shrink: 0;
    margin-top: 1px;
}

html.dark-theme .pvm-info-icon {
    color: #38bdf8;
}

.pvm-info-text {
    font-size: 12.5px;
    line-height: 1.5;
    color: var(--dash-text-muted, #64748b);
}

html.dark-theme .pvm-info-text {
    color: #94a3b8;
}

.pvm-info-text strong {
    color: var(--dash-text-dark, #1e293b);
}

html.dark-theme .pvm-info-text strong {
    color: #e2e8f0;
}

.pvm-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
}

.pvm-btn {
    width: 100%;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.18s ease;
    border: none;
    box-sizing: border-box;
    font-family: inherit;
}

.pvm-btn-primary {
    background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
}

.pvm-btn-primary:hover {
    background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(2, 132, 199, 0.45);
}

.pvm-btn-primary:active {
    transform: translateY(0);
}

.pvm-btn-secondary {
    background: var(--dash-input-bg, #f1f5f9);
    color: var(--dash-text-muted, #475569);
    border: 1px solid var(--dash-border, #e2e8f0);
}

.pvm-btn-secondary:hover {
    background: #e2e8f0;
    color: var(--dash-text-dark, #0f172a);
}

html.dark-theme .pvm-btn-secondary {
    background: #334155;
    color: #cbd5e1;
    border-color: #475569;
}

html.dark-theme .pvm-btn-secondary:hover {
    background: #475569;
    color: #ffffff;
}

@keyframes pvmFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes pvmScaleUp {
    from {
        opacity: 0;
        transform: scale(0.92) translateY(8px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes pvmPulse {
    0%, 100% {
        transform: scale(1);
        opacity: 0.6;
    }
    50% {
        transform: scale(1.15);
        opacity: 0.15;
    }
}
</style>

<script>
    function openPrintValidationModal() {
        const modal = document.getElementById('printValidationModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closePrintValidationModal() {
        const modal = document.getElementById('printValidationModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    function proceedPrintFromModal() {
        closePrintValidationModal();

        const printBtn = document.getElementById('btn_cetak_langsung');
        if (printBtn) {
            printBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
            printBtn.classList.remove('print-btn-shake');
            void printBtn.offsetWidth;
            printBtn.classList.add('print-btn-shake');
        }

        // Trigger cetak checklist
        if (typeof cetakLangsung === 'function') {
            cetakLangsung();
        }
    }

    // Close on click outside & Escape key
    (function() {
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('printValidationModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closePrintValidationModal();
                    }
                });
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('printValidationModal');
                    if (modal && modal.style.display !== 'none') {
                        closePrintValidationModal();
                    }
                }
            });
        });
    })();
</script>
