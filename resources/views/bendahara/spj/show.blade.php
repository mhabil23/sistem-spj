@extends('layouts.bendahara')

@php
$title = 'Detail Pencairan SPJ - Bendahara';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/bendahara/spj.css'])
@endpush

<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.5rem; color: #111827; margin: 0 0 0.5rem 0;">Pencairan SPJ: {{ Str::limit($spj->kegiatan, 40) }}</h1>
        <p>Diajukan pada {{ $spj->created_at->format('d M Y, H:i') }} oleh <strong>{{ $spj->user->name ?? 'Teknis' }}</strong>.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <button type="button" onclick="openWaModal()" style="background-color: #25d366; color: white; padding: 0.65rem 1rem; border-radius: 0.375rem; border: none; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 6px; cursor: pointer; transition: 0.2s; box-shadow: 0 2px 4px rgba(37, 211, 102, 0.25);" onmouseover="this.style.backgroundColor='#20bd5a';" onmouseout="this.style.backgroundColor='#25d366';">
            <ion-icon name="logo-whatsapp" style="font-size: 1.15rem;"></ion-icon> Kirim WhatsApp
        </button>
        <a href="{{ route('bendahara.spj.index') }}" class="btn-primary" style="background-color: #6b7280; text-decoration: none;">
            Kembali
        </a>
    </div>
</div>


@if(session('trigger_wa'))
<div style="background: linear-gradient(135deg, #ecfdf5, #d1fae5); border: 2px solid #10b981; border-radius: 12px; padding: 18px 24px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15); flex-wrap: wrap; gap: 16px;">
    <div style="display: flex; align-items: center; gap: 14px;">
        <div style="width: 44px; height: 44px; border-radius: 50%; background: #10b981; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.4);">
            <ion-icon name="logo-whatsapp" style="font-size: 26px;"></ion-icon>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #065f46;">Langkah Selanjutnya: Beritahu Pengaju via WhatsApp</h4>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: #047857;">Status SPJ berhasil diproses. Kirimkan pesan WhatsApp ke staf pengaju agar langsung mengetahui status pencairan/kendala.</p>
        </div>
    </div>
    <button type="button" onclick="openWaModal('{{ session('trigger_wa') }}')" style="background-color: #25d366; color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; cursor: pointer; white-space: nowrap; box-shadow: 0 4px 8px rgba(37,211,102,0.3); transition: 0.2s;" onmouseover="this.style.backgroundColor='#20bd5a';" onmouseout="this.style.backgroundColor='#25d366';">
        <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon> Buka Pesan WhatsApp
    </button>
</div>
@endif


<div class="panel" style="margin-bottom: 2rem;">
    <div class="panel-header">
        <h2>Detail Kegiatan & Biaya</h2>
    </div>
    <div style="padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div style="grid-column: span 2;">
            <span style="display: block; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Tanggal Kegiatan</span>
            <strong style="font-size: 1.125rem; color: #111827;">{{ $spj->tanggal->format('d F Y') }}</strong>
        </div>
        <div style="grid-column: span 2;">
            <span style="display: block; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Uraian Kegiatan</span>
            <strong style="font-size: 1.125rem; color: #111827;">{{ $spj->kegiatan }}</strong>
        </div>
        <div>
            <span style="display: block; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Keterangan / Catatan Teknis</span>
            <p style="color: #374151; margin: 0; white-space: pre-line;">{{ $spj->keterangan ?: '-' }}</p>
        </div>
    </div>
</div>

<div class="panel" style="margin-bottom: 2rem;">
    <div class="panel-header">
        <h2>Berkas Dokumen</h2>
    </div>
    <div style="padding: 1.5rem;">
        @if($spj->dokumen_file)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: #f9fafb;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="font-size: 2rem; color: #2563eb; display: flex; align-items: center;">
                        <ion-icon name="document-text-outline"></ion-icon>
                    </div>
                    <div>
                        <strong>Dokumen SPJ Lengkap</strong>
                        <div style="color: #6b7280; font-size: 0.875rem;">Lampiran dari pihak pengaju</div>
                    </div>
                </div>
                <a href="{{ asset('storage/' . $spj->dokumen_file) }}" target="_blank" class="btn-primary" style="text-decoration: none;">Lihat Dokumen Asli</a>
            </div>
        @else
            <div style="background-color: #fef2f2; color: #991b1b; padding: 1rem; border-radius: 0.375rem; border: 1px solid #f87171; text-align: center;">
                <strong style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                    <ion-icon name="warning-outline" style="font-size: 1.25rem;"></ion-icon> Tidak Ada Dokumen
                </strong>
            </div>
        @endif
    </div>
</div>

@if($spj->status == 'disetujui_ppspm')
<div class="panel">
    <div class="panel-header" style="background-color: #f3f4f6;">
        <h2>Eksekusi Pencairan Dana</h2>
    </div>
    <div style="padding: 1.5rem;">
        <form action="{{ route('bendahara.spj.verify', $spj->id) }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                
                <!-- OPSI SETUJUI & CAIRKAN -->
                <div style="border: 2px solid #0d9488; border-radius: 0.5rem; padding: 1.5rem; display: flex; flex-direction: column;">
                    <h3 style="color: #0f766e; margin-top: 0; margin-bottom: 1rem;">Opsi 1: Cairkan Dana & Selesaikan</h3>
                    <p style="color: #134e4a; margin-bottom: 1rem; font-size: 0.875rem;">Dana telah siap ditransfer atau diserahkan sesuai dengan SPM. Proses SPJ ini akan dianggap <strong>Selesai Sepenuhnya</strong>.</p>
                    
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #0f766e;">Bukti Transfer / No. Referensi (Wajib)</label>
                    <input type="text" name="bukti_transfer" style="width: 100%; padding: 0.75rem; border: 1px solid #5eead4; border-radius: 0.375rem; outline: none; margin-bottom: 1rem; background-color: #f0fdfa;" placeholder="Contoh: REF-BCA-92837492 atau Kas Tunai #12" required>
                    
                    <div style="display: flex; gap: 10px; margin-top: auto;">
                        <button type="submit" name="action" value="setujui" class="btn-primary" style="flex: 1; background-color: #0d9488; font-size: 1rem; padding: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; border: none; color: white;">
                            <ion-icon name="wallet-outline" style="font-size: 1.25rem;"></ion-icon> Cairkan & Tutup SPJ
                        </button>
                        <button type="button" onclick="openWaModalWithBukti('cair')" title="Kirim Konfirmasi Pencairan via WhatsApp" style="background-color: #25d366; color: white; border: none; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600; padding: 0 16px; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 6px rgba(37,211,102,0.25); white-space: nowrap; transition: 0.2s;" onmouseover="this.style.backgroundColor='#20bd5a';" onmouseout="this.style.backgroundColor='#25d366';">
                            <ion-icon name="logo-whatsapp" style="font-size: 1.25rem;"></ion-icon> Kirim WA
                        </button>
                    </div>
                </div>

                <!-- OPSI TOLAK -->
                <div style="border: 2px solid #ef4444; border-radius: 0.5rem; padding: 1.5rem; display: flex; flex-direction: column;">
                    <h3 style="color: #991b1b; margin-top: 0; margin-bottom: 0.5rem;">Opsi 2: Kembalikan (Revisi)</h3>
                    <p style="color: #7f1d1d; margin-bottom: 1rem; font-size: 0.875rem;">Ada ketidaksesuaian SPM atau masalah teknis bank. Kembalikan ke PPSPM / Teknis.</p>
                    
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #991b1b;">Alasan Penolakan (Wajib jika dikembalikan)</label>
                    <textarea name="catatan_revisi" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid #fca5a5; border-radius: 0.375rem; outline: none; margin-bottom: 1rem; background-color: #fef2f2;" placeholder="Tuliskan kendala pencairan dana..."></textarea>
                    
                    <div style="display: flex; gap: 10px; margin-top: auto;">
                        <button type="submit" name="action" value="tolak" class="btn-primary" style="flex: 1; background-color: #ef4444; font-size: 1rem; padding: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; border: none; color: white;">
                            <ion-icon name="close-circle-outline" style="font-size: 1.25rem;"></ion-icon> Kembalikan SPJ
                        </button>
                        <button type="button" onclick="openWaModalWithNote('revisi')" title="Kirim Catatan via WhatsApp" style="background-color: #25d366; color: white; border: none; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600; padding: 0 16px; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 6px rgba(37,211,102,0.25); white-space: nowrap; transition: 0.2s;" onmouseover="this.style.backgroundColor='#20bd5a';" onmouseout="this.style.backgroundColor='#25d366';">
                            <ion-icon name="logo-whatsapp" style="font-size: 1.25rem;"></ion-icon> Kirim WA
                        </button>
                    </div>
                </div>
                
            </div>
        </form>
    </div>
</div>
@endif

<!-- MODAL WHATSAPP MANUAL BENDAHARA -->
<div id="waModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px; box-sizing: border-box;">
    <div style="background: #ffffff; border-radius: 16px; max-width: 600px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden;">
        <div style="background: linear-gradient(135deg, #25d366, #128c7e); color: white; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                    <ion-icon name="logo-whatsapp" style="font-size: 26px;"></ion-icon>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 17px; font-weight: 700; color: white;">Kirim Notifikasi WhatsApp (Bendahara)</h3>
                    <small style="opacity: 0.9; font-size: 12px;">Template sudah terisi otomatis sesuai laporan SPJ ini</small>
                </div>
            </div>
            <button type="button" onclick="closeWaModal()" style="background: rgba(255,255,255,0.15); border: none; color: white; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; border-radius: 8px; transition: 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)';" onmouseout="this.style.background='rgba(255,255,255,0.15)';">
                <ion-icon name="close" style="font-size: 20px;"></ion-icon>
            </button>
        </div>

        <div style="padding: 24px;">
            @php
                $recipientWa = $spj->no_wa ?: ($spj->user->no_wa ?? '');
            @endphp
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                    Nomor WhatsApp Pengaju ({{ $spj->user->name ?? 'Teknis' }}):
                </label>
                <input type="text" id="waRecipient" value="{{ $recipientWa }}" placeholder="Contoh: 081234567890 (atau biarkan kosong untuk pilih kontak di WA)" style="width: 100%; box-sizing: border-box; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; font-family: inherit;">
                @if(empty($recipientWa))
                <small style="color: #d97706; font-size: 11px; display: flex; align-items: center; gap: 4px; margin-top: 5px;">
                    <ion-icon name="information-circle-outline"></ion-icon> Pengaju belum mencantumkan nomor WhatsApp pada SPJ ini. Anda bisa ketik nomor HP manual di atas, atau langsung klik "Buka di WhatsApp".
                </small>
                @endif
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 8px;">Pilih Template Pesan:</label>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <button type="button" id="btnTplCair" onclick="setWaTemplate('cair')" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 999px; border: 1px solid #0d9488; background: #f0fdfa; color: #0f766e; cursor: pointer; transition: 0.2s;">
                        🎉 Dana Dicairkan
                    </button>
                    <button type="button" id="btnTplRevisi" onclick="setWaTemplate('revisi')" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 999px; border: 1px solid #cbd5e1; background: #ffffff; color: #64748b; cursor: pointer; transition: 0.2s;">
                        ⚠️ Kendala / Revisi
                    </button>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label style="font-size: 13px; font-weight: 600; color: #334155;">Isi Pesan (Bisa Diedit Sesuai Kebutuhan):</label>
                    <button type="button" onclick="copyWaText()" id="btnCopyWa" style="background: none; border: none; color: #2563eb; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 4px;">
                        <ion-icon name="copy-outline"></ion-icon> <span>Salin Teks</span>
                    </button>
                </div>
                <textarea id="waMessageText" rows="9" style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; line-height: 1.5; font-family: inherit; outline: none; background: #f8fafc; color: #0f172a; resize: vertical;"></textarea>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="closeWaModal()" style="padding: 10px 18px; border: 1px solid #cbd5e1; background: #f1f5f9; color: #475569; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Tutup
                </button>
                <button type="button" onclick="sendWhatsAppNow()" style="padding: 10px 22px; border: none; background: #25d366; color: white; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px rgba(37, 211, 102, 0.25);">
                    <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon> Buka di WhatsApp
                </button>
            </div>
        </div>
    </div>
</div>

@php
    $spjJsData = [
        'pengaju' => $spj->user->name ?? 'Staf Teknis',
        'kegiatan' => $spj->kegiatan,
        'nilai' => 'Rp ' . number_format($spj->nilai, 0, ',', '.'),
        'catatanRevisi' => $spj->catatan_revisi ?? '',
        'buktiTransfer' => $spj->bukti_transfer ?? '',
        'linkDetail' => route('teknis.spj.show', $spj->id),
    ];
@endphp

@push('scripts')
<script>
    const spjData = @json($spjJsData);

    function openWaModal(defaultTpl = 'cair') {
        document.getElementById('waModal').style.display = 'flex';
        setWaTemplate(defaultTpl);
    }

    function openWaModalWithBukti(defaultTpl = 'cair') {
        const liveBuktiInput = document.querySelector('input[name="bukti_transfer"]');
        if (liveBuktiInput && liveBuktiInput.value.trim()) {
            spjData.activeBukti = liveBuktiInput.value.trim();
        } else {
            spjData.activeBukti = '';
        }
        openWaModal(defaultTpl);
    }

    function openWaModalWithNote(defaultTpl = 'revisi') {
        const liveCatatanInput = document.querySelector('textarea[name="catatan_revisi"]');
        if (liveCatatanInput && liveCatatanInput.value.trim()) {
            spjData.activeNote = liveCatatanInput.value.trim();
        } else {
            spjData.activeNote = '';
        }
        openWaModal(defaultTpl);
    }

    function closeWaModal() {
        document.getElementById('waModal').style.display = 'none';
    }

    window.addEventListener('click', function(e) {
        const modal = document.getElementById('waModal');
        if (e.target === modal) closeWaModal();
    });

    function setWaTemplate(tpl) {
        const btnCair = document.getElementById('btnTplCair');
        const btnRevisi = document.getElementById('btnTplRevisi');
        const textarea = document.getElementById('waMessageText');

        [btnCair, btnRevisi].forEach(btn => {
            btn.style.background = '#ffffff';
            btn.style.color = '#64748b';
            btn.style.borderColor = '#cbd5e1';
        });

        const liveBuktiInput = document.querySelector('input[name="bukti_transfer"]');
        const bukti = liveBuktiInput && liveBuktiInput.value.trim() ? liveBuktiInput.value.trim() : (spjData.buktiTransfer || 'Telah diproses transfer bank');

        const liveCatatanInput = document.querySelector('textarea[name="catatan_revisi"]');
        const catatan = liveCatatanInput && liveCatatanInput.value.trim() ? liveCatatanInput.value.trim() : (spjData.catatanRevisi || 'Kendala rekening / nomor rekening perlu dikonfirmasi.');

        let message = '';
        if (tpl === 'cair') {
            btnCair.style.background = '#f0fdfa';
            btnCair.style.color = '#0f766e';
            btnCair.style.borderColor = '#0d9488';

            message = `*PEMBERITAHUAN PENCAIRAN DANA SPJ*\n`
                    + `Bendahara Pengeluaran BPS Kab. Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Dana untuk SPJ Anda telah berhasil *DICAIRKAN*:\n\n`
                    + `• *Kegiatan*  : ${spjData.kegiatan}\n`
                    + `• *Nilai*     : ${spjData.nilai}\n`
                    + `• *Ref/Bukti* : ${bukti}\n\n`
                    + `Silakan periksa mutasi rekening Anda. Detail SPJ:\n`
                    + `${spjData.linkDetail}\n\n`
                    + `Terima kasih.\n`
                    + `_Bendahara BPS Kolaka Utara_`;
        } else {
            btnRevisi.style.background = '#fef2f2';
            btnRevisi.style.color = '#b91c1c';
            btnRevisi.style.borderColor = '#f87171';

            message = `*KENDALA PENCAIRAN DANA SPJ*\n`
                    + `Bendahara Pengeluaran BPS Kab. Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Terdapat kendala dalam pencairan SPJ Anda:\n\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Kendala / Catatan*:\n"${catatan}"\n\n`
                    + `Mohon segera konfirmasi ke Bendahara Pengeluaran.\n\n`
                    + `Terima kasih.\n`
                    + `_Bendahara BPS Kolaka Utara_`;
        }
        textarea.value = message;
    }

    function copyWaText() {
        const textarea = document.getElementById('waMessageText');
        textarea.select();
        navigator.clipboard.writeText(textarea.value).then(() => {
            const btnCopy = document.getElementById('btnCopyWa');
            const span = btnCopy.querySelector('span');
            span.textContent = 'Tersalin! ✔';
            btnCopy.style.color = '#10b981';
            setTimeout(() => {
                span.textContent = 'Salin Teks';
                btnCopy.style.color = '#2563eb';
            }, 2000);
        });
    }

    function sendWhatsAppNow() {
        let phone = document.getElementById('waRecipient').value.trim();
        const msg = document.getElementById('waMessageText').value.trim();
        if (!msg) return alert('Pesan tidak boleh kosong!');

        phone = phone.replace(/[^0-9]/g, '');
        if (phone.startsWith('0')) phone = '62' + phone.substring(1);
        else if (phone.startsWith('8')) phone = '62' + phone;

        let url = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(msg);
        if (phone) url = 'https://api.whatsapp.com/send?phone=' + phone + '&text=' + encodeURIComponent(msg);
        window.open(url, '_blank');
    }
</script>

@if(session('trigger_wa'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            openWaModal('{{ session('trigger_wa') }}');
        }, 350);
    });
</script>
@endif
@endpush
@endsection
