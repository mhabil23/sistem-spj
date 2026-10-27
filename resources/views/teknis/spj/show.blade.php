@extends('layouts.teknis')

@php
$title = 'Detail SPJ';
$subtitle = 'Informasi lengkap pengajuan SPJ Anda.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/spj.css'])
@endpush

<div class="dashboard-heading">
    <div>
        <h2 class="dashboard-heading-title">Detail Pengajuan SPJ</h2>
        <p class="dashboard-heading-subtitle">Diajukan pada: {{ $spj->created_at->format('d F Y, H:i') }} &bull; No: <strong>{{ $spj->nomor_spj ?: '-' }}</strong></p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        @if(in_array($spj->status, ['draft', 'dikembalikan', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara']))
        <a href="{{ route('teknis.spj.edit', $spj->id) }}" class="btn-dash-primary" style="background-color: var(--dash-peach-text) !important;">
            <ion-icon name="create-outline"></ion-icon>
            Edit SPJ
        </a>
        @endif
        <button type="button" onclick="openWaModal()" class="spj-btn" style="background: #25d366; color: white; border: none; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25); cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
            <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
            Hubungi Bagian Umum (WA)
        </button>
        <a href="{{ route('teknis.spj.index') }}" class="spj-btn spj-btn-secondary">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Kembali
        </a>
    </div>
</div>

<div class="spj-panel">
    <div class="spj-panel-header">
        <div>
            <h2>Data Utama SPJ</h2>
            <p>Rincian lengkap dari dokumen pertanggungjawaban yang diajukan.</p>
        </div>
        <div>
            @php
                $badgeClass = 'dash-badge-draft';
                if(in_array($spj->status, ['selesai', 'disetujui_ppk', 'disetujui_umum', 'disetujui_ppspm'])) $badgeClass = 'dash-badge-mint';
                elseif(in_array($spj->status, ['diajukan', 'menunggu_otp_kpa'])) $badgeClass = 'dash-badge-cyan';
                elseif(in_array($spj->status, ['dikembalikan', 'revisi_bendahara', 'revisi_ppk', 'revisi_ppspm', 'revisi_umum'])) $badgeClass = 'dash-badge-peach';
            @endphp
            <span class="dash-badge {{ $badgeClass }}" style="font-size: 13px; padding: 6px 16px;">
                <span class="badge-dot"></span>
                Status: {{ $spj->status === 'menunggu_otp_kpa' ? 'MENUNGGU OTP QLOLA KPA' : str_replace('_', ' ', strtoupper($spj->status)) }}
            </span>
        </div>
    </div>
    
    <div class="spj-panel-body">
        
        @if($spj->status === 'menunggu_otp_kpa')
            <div class="spj-alert" style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; align-items: flex-start; margin-bottom: 24px; padding: 14px 18px; border-radius: 10px; display: flex; gap: 12px;">
                <div style="flex-shrink: 0; font-size: 22px; color: #2563eb;">
                    <ion-icon name="key-outline"></ion-icon>
                </div>
                <div>
                    <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 4px; color: #1e40af;">Status: Menunggu OTP QLOLA KPA</h3>
                    <p style="font-weight: 400; margin: 0; line-height: 1.5; color: #1e3a8a; font-size: 13px;">
                        Transaksi telah diinput oleh Bendahara Pengeluaran ke BRI QLOLA dan saat ini sedang menunggu otorisasi OTP dari Kuasa Pengguna Anggaran (KPA). Pencairan dana ke rekening Anda akan segera terlaksana setelah otorisasi selesai.
                    </p>
                </div>
            </div>
        @elseif($spj->status === 'selesai')
            <div class="spj-alert" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; align-items: flex-start; margin-bottom: 24px; padding: 14px 18px; border-radius: 10px; display: flex; gap: 12px;">
                <div style="flex-shrink: 0; font-size: 22px; color: #16a34a;">
                    <ion-icon name="checkmark-done-circle"></ion-icon>
                </div>
                <div>
                    <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 4px; color: #166534;">Proses Pembayaran Telah Selesai</h3>
                    <p style="font-weight: 400; margin: 0; line-height: 1.5; color: #14532d; font-size: 13px;">
                        Dana telah berhasil diproses oleh Bendahara. <strong>Silahkan cek mutasi rekening Anda.</strong>
                        @if($spj->bukti_transfer)
                            <br><span style="display: inline-block; margin-top: 6px; font-size: 12px; background: #dcfce7; padding: 2px 8px; border-radius: 4px; border: 1px solid #86efac; color: #15803d;">Ref / No. Bukti: <strong>{{ $spj->bukti_transfer }}</strong></span>
                        @endif
                    </p>
                </div>
            </div>
        @endif

        @if(in_array($spj->status, ['dikembalikan', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara']) && $spj->catatan_revisi)
            <div class="spj-alert spj-alert-error" style="align-items: flex-start; margin-bottom: 24px;">
                <div style="flex-shrink: 0; font-size: 22px; color: var(--dash-peach-text);">
                    <ion-icon name="alert-circle"></ion-icon>
                </div>
                <div>
                    <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 4px; color: var(--dash-peach-text);">Catatan Revisi dari {{ strtoupper(explode('_', $spj->status)[1] ?? 'Pemeriksa') }}</h3>
                    <p style="font-weight: 400; margin: 0; white-space: pre-line; line-height: 1.5; color: var(--dash-text-dark);">{{ $spj->catatan_revisi }}</p>
                </div>
            </div>
        @endif

        <div class="spj-detail-grid">
            <div class="spj-detail-item">
                <div class="spj-detail-label">Nomor SPJ</div>
                <div class="spj-detail-value" style="font-weight: 700; color: var(--dash-text-dark);">{{ $spj->nomor_spj ?: '-' }}</div>
            </div>

            <div class="spj-detail-item">
                <div class="spj-detail-label">Tanggal Kegiatan</div>
                <div class="spj-detail-value">{{ $spj->tanggal->format('d F Y') }}</div>
            </div>

            <div class="spj-detail-item">
                <div class="spj-detail-label">Nilai</div>
                <div class="spj-detail-value" style="color: var(--dash-mint-text);">Rp {{ number_format($spj->nilai, 0, ',', '.') }}</div>
            </div>

            <div class="spj-detail-item">
                <div class="spj-detail-label">WhatsApp PIC / Pengaju</div>
                <div class="spj-detail-value" style="color: var(--dash-cyan-text);">{{ $spj->no_wa ?: '-' }}</div>
            </div>
        </div>

        <div style="margin-top: 16px;" class="spj-detail-item">
            <div class="spj-detail-label">Uraian Kegiatan</div>
            <div class="spj-detail-value" style="font-size: 15px; font-weight: 600; line-height: 1.6;">{{ $spj->kegiatan }}</div>
        </div>

        @if($spj->keterangan)
        <div style="margin-top: 16px;" class="spj-detail-item">
            <div class="spj-detail-label">Keterangan / Catatan Teknis</div>
            <div class="spj-detail-value" style="font-size: 14px; line-height: 1.6; color: var(--dash-text-muted);">{{ $spj->keterangan }}</div>
        </div>
        @endif
        
        <div style="margin-top: 28px;">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--dash-text-dark); margin-bottom: 14px; border-bottom: 1px solid var(--dash-border); padding-bottom: 8px;">Kelengkapan Dokumen Fisik</h3>
            
            <div style="background: var(--dash-input-bg); border: 1px solid var(--dash-border); border-radius: 12px; padding: 20px;">
                @if($spj->dokumen_file)
                    @php
                        $dokumens = json_decode($spj->dokumen_file, true);
                    @endphp
                    
                    @if(is_array($dokumens) && count($dokumens) > 0)
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 14px;">
                            @foreach($dokumens as $doc)
                                <div style="display: flex; align-items: flex-start; gap: 12px; padding: 12px; background: var(--dash-card-bg); border: 1px solid {{ $doc['disiapkan'] ? 'var(--dash-mint-text)' : 'var(--dash-border)' }}; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                                    <div style="margin-top: 2px;">
                                        @if($doc['disiapkan'])
                                            <ion-icon name="checkmark-circle" style="color: var(--dash-mint-text); font-size: 20px;"></ion-icon>
                                        @else
                                            <ion-icon name="close-circle" style="color: var(--dash-text-muted); font-size: 20px;"></ion-icon>
                                        @endif
                                    </div>
                                    <div>
                                        <span style="font-weight: 600; font-size: 13px; color: {{ $doc['disiapkan'] ? 'var(--dash-mint-text)' : 'var(--dash-text-muted)' }};">{{ $doc['nama'] }}</span>
                                        <div style="font-size: 11px; color: var(--dash-text-muted); margin-top: 2px;">
                                            {{ $doc['disiapkan'] ? 'Telah disiapkan (Fisik)' : 'Tidak diwajibkan untuk Teknis' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="color: var(--dash-text-muted); font-style: italic; margin: 0; font-size: 13px;">Data dokumen tidak valid atau kosong.</p>
                    @endif
                @else
                    <p style="color: var(--dash-text-muted); font-style: italic; margin: 0; font-size: 13px;">Belum ada daftar dokumen kelengkapan.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- MODAL WHATSAPP MANUAL TEKNIS -->
<div id="waModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px; box-sizing: border-box;">
    <div style="background: var(--dash-card-bg); border-radius: 16px; max-width: 600px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden; border: 1px solid var(--dash-border);">
        <div style="background: linear-gradient(135deg, #25d366, #128c7e); color: white; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <ion-icon name="logo-whatsapp"></ion-icon>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: white;">Hubungi Bagian Umum via WhatsApp</h3>
                    <small style="opacity: 0.9; font-size: 12px;">Pesan otomatis terisi sesuai laporan SPJ ini</small>
                </div>
            </div>
            <button type="button" onclick="closeWaModal()" style="background: rgba(255,255,255,0.15); border: none; color: white; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                <ion-icon name="close"></ion-icon>
            </button>
        </div>

        <div style="padding: 24px;">
            @php
                $umumWa = \App\Models\User::where('role', 'umum')->whereNotNull('no_wa')->value('no_wa');
            @endphp
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--dash-text-dark); margin-bottom: 6px;">
                    Nomor WhatsApp Tujuan (Petugas Bagian Umum):
                </label>
                <input type="text" id="waRecipient" value="{{ $umumWa ?? '' }}" placeholder="Contoh: 081234567890 (atau biarkan kosong untuk pilih kontak di WA)" style="width: 100%; box-sizing: border-box; padding: 10px 14px; border: 1px solid var(--dash-input-border); border-radius: 8px; font-size: 13px; outline: none; background: var(--dash-input-bg); color: var(--dash-text-dark); font-family: inherit;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--dash-text-dark); margin-bottom: 8px;">Pilih Template Pesan:</label>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <button type="button" id="btnTplKonfirm" onclick="setWaTemplate('konfirm')" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 20px; border: 1px solid #10b981; background: #ecfdf5; color: #047857; cursor: pointer;">
                        📤 Konfirmasi Pengajuan
                    </button>
                    <button type="button" id="btnTplTanya" onclick="setWaTemplate('tanya')" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 20px; border: 1px solid var(--dash-border); background: var(--dash-card-bg); color: var(--dash-text-muted); cursor: pointer;">
                        ❓ Tanya Kelengkapan
                    </button>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label style="font-size: 13px; font-weight: 600; color: var(--dash-text-dark);">Isi Pesan (Bisa Diedit):</label>
                    <button type="button" onclick="copyWaText()" id="btnCopyWa" style="background: none; border: none; color: var(--dash-cyan-text); font-size: 12px; font-weight: 600; cursor: pointer;">
                        <span>Salin Teks</span>
                    </button>
                </div>
                <textarea id="waMessageText" rows="8" style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid var(--dash-input-border); border-radius: 8px; font-size: 13px; line-height: 1.5; font-family: inherit; outline: none; background: var(--dash-input-bg); color: var(--dash-text-dark); resize: vertical;"></textarea>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeWaModal()" class="spj-btn spj-btn-secondary">
                    Tutup
                </button>
                <button type="button" onclick="sendWhatsAppNow()" style="padding: 10px 20px; border: none; background: #25d366; color: white; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);">
                    <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
                    Buka di WhatsApp
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
        const txt = document.getElementById('waMessageText');

        if (tpl === 'konfirm') {
            btnKonfirm.style.background = '#ecfdf5';
            btnKonfirm.style.color = '#047857';
            btnKonfirm.style.borderColor = '#10b981';

            btnTanya.style.background = 'var(--dash-card-bg)';
            btnTanya.style.color = 'var(--dash-text-muted)';
            btnTanya.style.borderColor = 'var(--dash-border)';

            txt.value = `Halo Bagian Umum BPS,\n\nSaya ingin mengonfirmasi bahwa pengajuan SPJ berikut telah saya submit melalui Sistem SPJ:\n\n• Pengaju: ${spjData.pengaju}\n• Kegiatan: ${spjData.kegiatan}\n• Nilai: ${spjData.nilai}\n• Status Saat Ini: ${spjData.status}\n\nMohon bantuannya untuk dilakukan pemeriksaan dan verifikasi kelengkapan berkas fisik. Terima kasih banyak!\n\nLink Sistem:\n${spjData.linkDetail}`;
        } else {
            btnTanya.style.background = '#eff6ff';
            btnTanya.style.color = '#1d4ed8';
            btnTanya.style.borderColor = '#3b82f6';

            btnKonfirm.style.background = 'var(--dash-card-bg)';
            btnKonfirm.style.color = 'var(--dash-text-muted)';
            btnKonfirm.style.borderColor = 'var(--dash-border)';

            txt.value = `Halo Petugas Verifikator Bagian Umum,\n\nMohon izin bertanya terkait kelengkapan berkas untuk SPJ kegiatan:\n"${spjData.kegiatan}" (${spjData.nilai}).\n\nApakah ada dokumen pendukung tambahan yang masih kurang atau perlu saya revisi agar proses verifikasi bisa segera berlanjut? Terima kasih atas arahannya!`;
        }
    }

    function sendWhatsAppNow() {
        let phone = document.getElementById('waRecipient').value.trim();
        const text = encodeURIComponent(document.getElementById('waMessageText').value);

        phone = phone.replace(/[^0-9]/g, '');
        if (phone.startsWith('0')) {
            phone = '62' + phone.substring(1);
        }

        let url = phone ? `https://api.whatsapp.com/send?phone=${phone}&text=${text}` : `https://api.whatsapp.com/send?text=${text}`;
        window.open(url, '_blank');
    }

    function copyWaText() {
        const text = document.getElementById('waMessageText').value;
        navigator.clipboard.writeText(text).then(() => {
            const btn = document.getElementById('btnCopyWa');
            btn.innerHTML = '<span style="color: #10b981;">✓ Tersalin!</span>';
            setTimeout(() => {
                btn.innerHTML = '<span>Salin Teks</span>';
            }, 2000);
        });
    }
</script>
@endpush

@endsection
