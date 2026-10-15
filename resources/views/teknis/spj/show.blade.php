@extends('layouts.teknis')

@php
$title = 'Detail SPJ';
$subtitle = 'Informasi lengkap pengajuan SPJ Anda.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/spj.css'])
@endpush

<div class="page-heading" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; animation: slideUp 0.4s ease-out forwards;">
    <div>
        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">Detail Pengajuan SPJ</h2>
        <p style="color: #64748b; font-size: 0.95rem;">Diajukan pada: {{ $spj->created_at->format('d F Y, H:i') }}</p>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: center;">
        @if(in_array($spj->status, ['draft', 'dikembalikan', 'revisi_bendahara', 'revisi_ppk', 'revisi_ppspm']))
        <a href="{{ route('teknis.spj.edit', $spj->id) }}" class="spj-btn spj-btn-primary" style="background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            Edit SPJ
        </a>
        @endif
        <button type="button" onclick="openWaModal()" class="spj-btn" style="background: #25d366; color: white; border: none; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25); cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
              <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.016-.348-.05-.1-.445-1.075-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
            </svg>
            Hubungi Umum (WA)
        </button>
        <a href="{{ route('teknis.spj.index') }}" class="spj-btn spj-btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Kembali
        </a>
    </div>
</div>

<div class="spj-panel" style="animation-delay: 0.1s;">
    <div class="spj-panel-header">
        <div>
            <h2>Data Utama SPJ</h2>
            <p>Rincian lengkap dari dokumen pertanggungjawaban.</p>
        </div>
        <div>
            @php
                $badgeClass = 'spj-badge-draft';
                if(in_array($spj->status, ['selesai'])) $badgeClass = 'spj-badge-selesai';
                elseif(in_array($spj->status, ['diajukan', 'disetujui_ppk'])) $badgeClass = 'spj-badge-diajukan';
                elseif(in_array($spj->status, ['disetujui_ppspm'])) $badgeClass = 'spj-badge-disetujui';
                elseif(in_array($spj->status, ['dikembalikan', 'revisi_bendahara', 'revisi_ppk', 'revisi_ppspm'])) $badgeClass = 'spj-badge-dikembalikan';
            @endphp
            <span class="spj-badge {{ $badgeClass }}" style="font-size: 1rem; padding: 0.5rem 1.25rem;">
                Status: {{ str_replace('_', ' ', strtoupper($spj->status)) }}
            </span>
        </div>
    </div>
    
    <div class="spj-panel-body">
        
        @if(in_array($spj->status, ['dikembalikan', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara']) && $spj->catatan_revisi)
            <div class="spj-alert spj-alert-error" style="align-items: flex-start; margin-bottom: 2rem;">
                <div style="flex-shrink: 0; margin-top: 0.125rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.25rem;">Catatan Revisi dari {{ strtoupper(explode('_', $spj->status)[1] ?? 'Pemeriksa') }}</h3>
                    <p style="font-weight: 400; margin: 0; white-space: pre-line; line-height: 1.5;">{{ $spj->catatan_revisi }}</p>
                </div>
            </div>
        @endif

        <div class="spj-detail-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
            
            <div class="spj-detail-item">
                <div class="spj-detail-label">Nomor SPJ</div>
                <div class="spj-detail-value" style="font-weight: 700; color: #344054;">{{ $spj->nomor_spj }}</div>
            </div>

            <div class="spj-detail-item">
                <div class="spj-detail-label">Tanggal Kegiatan</div>
                <div class="spj-detail-value">{{ $spj->tanggal->format('d F Y') }}</div>
            </div>

            <div class="spj-detail-item">
                <div class="spj-detail-label">Nilai</div>
                <div class="spj-detail-value" style="font-weight: 600;">Rp {{ number_format($spj->nilai, 0, ',', '.') }}</div>
            </div>

            <div class="spj-detail-item">
                <div class="spj-detail-label">WhatsApp PIC / Pengaju</div>
                <div class="spj-detail-value" style="font-weight: 600; color: #0d9488;">{{ $spj->no_wa ?: '-' }}</div>
            </div>

        </div>

        <div style="margin-top: 1.5rem;" class="spj-detail-item">
            <div class="spj-detail-label">Uraian Kegiatan</div>
            <div class="spj-detail-value" style="font-size: 1rem; font-weight: 500; line-height: 1.6;">{{ $spj->kegiatan }}</div>
        </div>

        @if($spj->keterangan)
        <div style="margin-top: 1.5rem;" class="spj-detail-item">
            <div class="spj-detail-label">Keterangan / Catatan Teknis</div>
            <div class="spj-detail-value" style="font-size: 0.95rem; line-height: 1.6; color: #475569;">{{ $spj->keterangan }}</div>
        </div>
        @endif
        
        <div style="margin-top: 2rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem;">Kelengkapan Dokumen Fisik</h3>
            
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem;">
                @if($spj->dokumen_file)
                    @php
                        $dokumens = json_decode($spj->dokumen_file, true);
                    @endphp
                    
                    @if(is_array($dokumens) && count($dokumens) > 0)
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
                            @foreach($dokumens as $doc)
                                <div style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem; background: white; border: 1px solid {{ $doc['disiapkan'] ? '#86efac' : '#e2e8f0' }}; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                    <div style="margin-top: 0.125rem;">
                                        @if($doc['disiapkan'])
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <span style="font-weight: 500; font-size: 0.9rem; color: {{ $doc['disiapkan'] ? '#166534' : '#64748b' }};">{{ $doc['nama'] }}</span>
                                        <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">
                                            {{ $doc['disiapkan'] ? 'Telah disiapkan (Fisik)' : 'Tidak diwajibkan untuk Teknis' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="color: #64748b; font-style: italic; margin: 0;">Data dokumen tidak valid atau kosong.</p>
                    @endif
                @else
                    <p style="color: #64748b; font-style: italic; margin: 0;">Belum ada daftar dokumen kelengkapan.</p>
                @endif
            </div>
        </div>
        
        <!-- Track Record / History Flow could be added here in the future -->
    </div>
</div>

<!-- MODAL WHATSAPP MANUAL TEKNIS -->
<div id="waModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px; box-sizing: border-box;">
    <div style="background: #ffffff; border-radius: 16px; max-width: 600px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden;">
        <div style="background: linear-gradient(135deg, #25d366, #128c7e); color: white; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                      <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.016-.348-.05-.1-.445-1.075-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                    </svg>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: white;">Hubungi Bagian Umum via WhatsApp</h3>
                    <small style="opacity: 0.9; font-size: 0.8rem;">Pesan otomatis terisi sesuai laporan SPJ ini</small>
                </div>
            </div>
            <button type="button" onclick="closeWaModal()" style="background: rgba(255,255,255,0.15); border: none; color: white; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                ✕
            </button>
        </div>

        <div style="padding: 1.5rem;">
            @php
                $umumWa = \App\Models\User::where('role', 'umum')->whereNotNull('no_wa')->value('no_wa');
            @endphp
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #334155; margin-bottom: 0.35rem;">
                    Nomor WhatsApp Tujuan (Petugas Bagian Umum):
                </label>
                <input type="text" id="waRecipient" value="{{ $umumWa ?? '' }}" placeholder="Contoh: 081234567890 (atau biarkan kosong untuk pilih kontak di WA)" style="width: 100%; box-sizing: border-box; padding: 0.65rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; font-size: 0.9rem; outline: none; font-family: inherit;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">Pilih Template Pesan:</label>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button type="button" id="btnTplKonfirm" onclick="setWaTemplate('konfirm')" style="padding: 0.4rem 0.85rem; font-size: 0.8rem; font-weight: 600; border-radius: 999px; border: 1px solid #10b981; background: #ecfdf5; color: #047857; cursor: pointer;">
                        📤 Konfirmasi Pengajuan
                    </button>
                    <button type="button" id="btnTplTanya" onclick="setWaTemplate('tanya')" style="padding: 0.4rem 0.85rem; font-size: 0.8rem; font-weight: 600; border-radius: 999px; border: 1px solid #cbd5e1; background: #ffffff; color: #64748b; cursor: pointer;">
                        ❓ Tanya Kelengkapan
                    </button>
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                    <label style="font-size: 0.85rem; font-weight: 600; color: #334155;">Isi Pesan (Bisa Diedit):</label>
                    <button type="button" onclick="copyWaText()" id="btnCopyWa" style="background: none; border: none; color: #2563eb; font-size: 0.8rem; font-weight: 600; cursor: pointer;">
                        <span>Salin Teks</span>
                    </button>
                </div>
                <textarea id="waMessageText" rows="9" style="width: 100%; box-sizing: border-box; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; font-size: 0.85rem; line-height: 1.5; font-family: inherit; outline: none; background: #f8fafc; color: #0f172a; resize: vertical;"></textarea>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="button" onclick="closeWaModal()" style="padding: 0.65rem 1.15rem; border: 1px solid #cbd5e1; background: #f1f5f9; color: #475569; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                    Tutup
                </button>
                <button type="button" onclick="sendWhatsAppNow()" style="padding: 0.65rem 1.35rem; border: none; background: #25d366; color: white; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 6px rgba(37, 211, 102, 0.25);">
                    Buka di WhatsApp
                </button>
            </div>
        </div>
    </div>
</div>

@php
    $spjJsData = [
        'nomor' => $spj->nomor_spj,
        'pengaju' => $spj->user->name ?? 'Staf Teknis',
        'kegiatan' => $spj->kegiatan,
        'nilai' => 'Rp ' . number_format($spj->nilai, 0, ',', '.'),
        'status' => strtoupper(str_replace('_', ' ', $spj->status)),
        'linkDetail' => url('/umum/spj/' . $spj->id),
    ];
@endphp

@push('scripts')
<script>
    const spjData = @json($spjJsData);

    function openWaModal(defaultTpl = 'konfirm') {
        document.getElementById('waModal').style.display = 'flex';
        setWaTemplate(defaultTpl);
    }

    function closeWaModal() {
        document.getElementById('waModal').style.display = 'none';
    }

    window.addEventListener('click', function(e) {
        const modal = document.getElementById('waModal');
        if (e.target === modal) closeWaModal();
    });

    function setWaTemplate(tpl) {
        const btnKonfirm = document.getElementById('btnTplKonfirm');
        const btnTanya = document.getElementById('btnTplTanya');
        const textarea = document.getElementById('waMessageText');

        [btnKonfirm, btnTanya].forEach(btn => {
            btn.style.background = '#ffffff';
            btn.style.color = '#64748b';
            btn.style.borderColor = '#cbd5e1';
        });

        let message = '';
        if (tpl === 'konfirm') {
            btnKonfirm.style.background = '#ecfdf5';
            btnKonfirm.style.color = '#047857';
            btnKonfirm.style.borderColor = '#10b981';

            message = `*KONFIRMASI PENGAJUAN SPJ*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Yth. Tim Verifikasi Umum BPS,\n\n`
                    + `Saya telah mengajukan dokumen SPJ melalui sistem SPJ:\n\n`
                    + `• *No. SPJ*  : ${spjData.nomor}\n`
                    + `• *Pengaju*  : ${spjData.pengaju}\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Nilai*    : ${spjData.nilai}\n\n`
                    + `Mohon bantuannya untuk diperiksa dan diproses. Tautan dokumen:\n`
                    + `${spjData.linkDetail}\n\n`
                    + `Terima kasih banyak.\n`
                    + `_Salam, ${spjData.pengaju}_`;
        } else {
            btnTanya.style.background = '#eff6ff';
            btnTanya.style.color = '#1d4ed8';
            btnTanya.style.borderColor = '#60a5fa';

            message = `*KONSULTASI KELENGKAPAN BERKAS SPJ*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Halo Tim Umum,\n\n`
                    + `Terkait pengajuan SPJ *${spjData.nomor}* (${spjData.kegiatan}), mohon petunjuk apakah berkas fisik yang saya siapkan sudah lengkap atau ada dokumen pendukung lain yang perlu ditambahkan?\n\n`
                    + `Terima kasih.\n`
                    + `_Salam, ${spjData.pengaju}_`;
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
@endpush
@endsection
