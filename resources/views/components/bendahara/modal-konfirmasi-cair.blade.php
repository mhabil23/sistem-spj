@props(['spj'])

<!-- MODAL KONFIRMASI ULANG PENYELESAIAN PEMBAYARAN -->
<div id="modalKonfirmasiCair" class="bnd-modal-overlay" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="modalCairTitle">
    <div class="bnd-modal-card" style="max-width: 580px; box-shadow: 0 25px 60px -15px rgba(5, 150, 105, 0.35);">
        
        <!-- MODAL HEADER -->
        <div class="bnd-modal-head" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
            <div class="bnd-modal-head-left">
                <div class="bnd-modal-head-icon" style="background: rgba(255, 255, 255, 0.2); width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <ion-icon name="shield-checkmark" style="font-size: 26px; color: #ffffff;"></ion-icon>
                </div>
                <div>
                    <h3 id="modalCairTitle" class="bnd-modal-head-title" style="font-size: 17px; margin: 0 0 2px 0;">Konfirmasi Ulang Pembayaran</h3>
                    <p class="bnd-modal-head-sub" style="color: rgba(255, 255, 255, 0.9); margin: 0; font-size: 12px;">
                        Pastikan seluruh data &amp; transaksi transfer telah sesuai sebelum menyelesaikan SPJ
                    </p>
                </div>
            </div>
            <button type="button" onclick="tutupModalKonfirmasiCair()" class="bnd-modal-close" style="background: none; border: none; color: #ffffff; cursor: pointer; font-size: 22px; display: flex; align-items: center; justify-content: center; opacity: 0.85; transition: 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.85'" aria-label="Tutup modal">
                <ion-icon name="close"></ion-icon>
            </button>
        </div>

        <!-- MODAL BODY -->
        <div class="bnd-modal-body" style="padding: 24px;">
            
            <!-- RINGKASAN DATA SPJ -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 18px; margin-bottom: 18px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px dashed #cbd5e1;">
                    <div>
                        <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">Nomor SPJ</span>
                        <div style="font-size: 14px; font-weight: 800; color: #0284c7; margin-top: 3px;">
                            {{ $spj->nomor_spj }}
                        </div>
                    </div>
                    <div>
                        <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">Total Nilai Dicairkan</span>
                        <div style="font-size: 16px; font-weight: 800; color: #059669; margin-top: 2px;">
                            Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 12px;">
                    <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">Uraian Kegiatan</span>
                    <div style="font-size: 13px; font-weight: 600; color: #0f172a; margin-top: 3px; line-height: 1.45;">
                        {{ $spj->kegiatan }}
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 12px;">
                    <div>
                        <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">Pelaksana / Pengaju</span>
                        <div style="font-size: 13px; font-weight: 700; color: #334155; margin-top: 2px;">
                            {{ $spj->user->name ?? 'Staf Teknis' }}
                        </div>
                    </div>
                    <div>
                        <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">Metode Pencairan</span>
                        <div id="modalCairMetode" style="font-size: 12.5px; font-weight: 700; color: #0369a1; margin-top: 2px;">
                            -
                        </div>
                    </div>
                </div>

                <div style="padding-top: 10px; border-top: 1px dashed #cbd5e1;">
                    <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">Nomor Bukti / Referensi Bank</span>
                    <div id="modalCairRef" style="font-size: 13.5px; font-weight: 800; color: #0f172a; margin-top: 2px; font-family: monospace, sans-serif;">
                        -
                    </div>
                </div>
            </div>

            <!-- CHECKLIST PERINGATAN KONFIRMASI -->
            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 14px 16px; margin-bottom: 22px;">
                <div style="display: flex; gap: 10px; align-items: flex-start;">
                    <ion-icon name="checkmark-circle" style="font-size: 22px; color: #059669; flex-shrink: 0; margin-top: 1px;"></ion-icon>
                    <div style="font-size: 12.5px; color: #065f46; line-height: 1.55;">
                        <strong style="display: block; font-size: 13px; margin-bottom: 4px; color: #047857;">Poin Pemeriksaan Terakhir:</strong>
                        <ul style="margin: 0; padding-left: 18px;">
                            <li>Dana telah <strong>sukses didebit / ditransfer</strong> ke rekening penerima yang berhak.</li>
                            <li>Nomor bukti transaksi perbankan telah diperiksa dan sesuai.</li>
                            <li>Status SPJ akan resmi dikunci menjadi <span style="background: #d1fae5; color: #047857; font-weight: 700; padding: 1px 6px; border-radius: 4px;">SELESAI</span> dan dicatat pada riwayat arsip kas.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- TOMBOL AKSI MODAL -->
            <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                <button type="button" onclick="tutupModalKonfirmasiCair()" class="bnd-btn bnd-btn-back" style="height: 42px; padding: 0 18px;">
                    <ion-icon name="arrow-back-outline"></ion-icon>
                    <span>Periksa Kembali</span>
                </button>
                <button type="button" id="btnEksekusiSelesai" onclick="eksekusiSelesaiPembayaran()" class="bnd-btn" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #ffffff; height: 42px; padding: 0 24px; font-weight: 700; font-size: 13.5px; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.3); border: none; border-radius: 10px; cursor: pointer;">
                    <ion-icon name="checkmark-done-circle" style="font-size: 19px;"></ion-icon>
                    <span>Ya, Selesaikan &amp; Cairkan Sekarang</span>
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    let pendingCairForm = null;

    function bukaModalKonfirmasiCair(event, formElement, metodeText) {
        if (event) {
            event.preventDefault();
        }
        pendingCairForm = formElement;

        const inputBukti = formElement.querySelector('input[name="bukti_transfer"]');
        const buktiVal = inputBukti && inputBukti.value.trim() ? inputBukti.value.trim() : '(Tanpa Nomor Bukti / Ref)';

        const modalMetode = document.getElementById('modalCairMetode');
        const modalRef = document.getElementById('modalCairRef');
        const modal = document.getElementById('modalKonfirmasiCair');

        if (modalMetode) modalMetode.textContent = metodeText;
        if (modalRef) modalRef.textContent = buktiVal;

        if (modal) {
            modal.style.display = 'flex';
        }

        return false;
    }

    function tutupModalKonfirmasiCair() {
        const modal = document.getElementById('modalKonfirmasiCair');
        if (modal) {
            modal.style.display = 'none';
        }
        pendingCairForm = null;
    }

    function eksekusiSelesaiPembayaran() {
        if (pendingCairForm) {
            const btn = document.getElementById('btnEksekusiSelesai');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span>Memproses...</span>';
            }

            // Pastikan input action=setujui disertakan
            let actionInput = pendingCairForm.querySelector('input[name="action"]');
            if (!actionInput) {
                actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'setujui';
                pendingCairForm.appendChild(actionInput);
            } else {
                actionInput.value = 'setujui';
            }

            pendingCairForm.submit();
        }
    }

    window.addEventListener('click', function(e) {
        const modal = document.getElementById('modalKonfirmasiCair');
        if (e.target === modal) {
            tutupModalKonfirmasiCair();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            tutupModalKonfirmasiCair();
        }
    });
</script>

