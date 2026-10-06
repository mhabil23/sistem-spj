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
    <div style="display: flex; gap: 0.75rem;">
        @if(in_array($spj->status, ['draft', 'dikembalikan', 'revisi_bendahara', 'revisi_ppk', 'revisi_ppspm']))
        <a href="{{ route('teknis.spj.edit', $spj->id) }}" class="spj-btn spj-btn-primary" style="background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            Edit SPJ
        </a>
        @endif
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

        <div class="spj-detail-grid" style="grid-template-columns: 1fr;">
            
            <div class="spj-detail-item">
                <div class="spj-detail-label">Tanggal Kegiatan</div>
                <div class="spj-detail-value">{{ $spj->tanggal->format('d F Y') }}</div>
            </div>
        </div>

        <div style="margin-top: 1.5rem;" class="spj-detail-item">
            <div class="spj-detail-label">Uraian Kegiatan</div>
            <div class="spj-detail-value" style="font-size: 1rem; font-weight: 500; line-height: 1.6;">{{ $spj->kegiatan }}</div>
        </div>
        
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

@endsection
