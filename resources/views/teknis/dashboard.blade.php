@extends('layouts.teknis')

@php
$title = 'Dashboard';
$subtitle = 'Pantau dan kelola pengajuan SPJ Anda.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/dashboard.css'])
@endpush



<!-- PAGE HEADER -->

<div class="page-heading">

    <div>

        <h2>
            Selamat Datang, Pengguna Teknis
        </h2>

        <p>
            Berikut ringkasan pengajuan SPJ Anda.
        </p>

    </div>


    <a href="{{ route('teknis.spj.create') }}" class="btn-primary">
        <span>+</span> Ajukan SPJ
    </a>

</div>



<!-- STATISTIK -->

<section class="stats-grid">


    <!-- TOTAL -->
    <div class="stat-card">
        <div class="stat-icon blue">▣</div>
        <div class="stat-content">
            <span>Total SPJ</span>
            <strong>{{ $totalSpj }}</strong>
            <small>Semua pengajuan Anda</small>
        </div>
    </div>

    <!-- DIPROSES -->
    <div class="stat-card">
        <div class="stat-icon orange">◷</div>
        <div class="stat-content">
            <span>Sedang Diproses</span>
            <strong>{{ $diprosesSpj }}</strong>
            <small>Menunggu pemeriksaan</small>
        </div>
    </div>

    <!-- DIKEMBALIKAN -->
    <div class="stat-card">
        <div class="stat-icon red">!</div>
        <div class="stat-content">
            <span>Dikembalikan</span>
            <strong>{{ $dikembalikanSpj }}</strong>
            <small>Perlu diperbaiki</small>
        </div>
    </div>

    <!-- SELESAI -->
    <div class="stat-card">
        <div class="stat-icon green">✓</div>
        <div class="stat-content">
            <span>Selesai</span>
            <strong>{{ $selesaiSpj }}</strong>
            <small>SPJ telah selesai</small>
        </div>
    </div>

</section>



<!-- CONTENT GRID -->

<section class="dashboard-grid">


    <!-- SPJ TERBARU -->

    <div class="panel">

        <div class="panel-header">

            <div>

                <h2>
                    SPJ Saya
                </h2>

                <p>
                    Pengajuan SPJ terbaru
                </p>

            </div>

            <a href="{{ route('teknis.spj.index') }}">Lihat semua →</a>

        </div>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>
                            Nomor SPJ
                        </th>

                        <th>
                            Kegiatan
                        </th>

                        <th>
                            Nilai
                        </th>

                        <th>
                            Tahapan
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>
                    @forelse($recentSpjs as $spj)
                    <tr>
                        <td><strong>{{ $spj->nomor_spj }}</strong></td>
                        <td>{{ $spj->kegiatan }}</td>
                        <td>Rp {{ number_format($spj->nilai, 0, ',', '.') }}</td>
                        <td>
                            @if(in_array($spj->status, ['draft', 'revisi_umum', 'revisi_ppk', 'revisi_bendahara']))
                                Teknis
                            @elseif($spj->status == 'diajukan')
                                Umum/PPSPM
                            @elseif($spj->status == 'disetujui_umum')
                                PPK
                            @elseif($spj->status == 'disetujui_ppk')
                                PPSPM
                            @elseif($spj->status == 'disetujui_ppspm')
                                Bendahara
                            @elseif($spj->status == 'selesai')
                                Arsip
                            @else
                                {{ ucfirst($spj->status) }}
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeClass = 'pending';
                                if($spj->status == 'selesai') $badgeClass = 'completed';
                                if(str_contains($spj->status, 'revisi')) $badgeClass = 'returned';
                                if($spj->status == 'draft') $badgeClass = 'draft';
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ str_replace('_', ' ', ucfirst($spj->status)) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 20px;">Belum ada pengajuan SPJ</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>



    <!-- STATUS SPJ -->

    <div class="panel">

        <div class="panel-header">

            <div>

                <h2>
                    Status SPJ
                </h2>

                <p>
                    Posisi pengajuan terbaru
                </p>

            </div>

        </div>


        <div class="status-list">

            @forelse($recentSpjs->take(3) as $spj)
            <div class="status-item">
                @php
                    $isDone = $spj->status == 'selesai';
                    $isError = str_contains($spj->status, 'revisi');
                    $isPending = in_array($spj->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk']);
                    $icon = $isDone ? '✓' : ($isError ? '!' : '◷');
                    $class = $isDone ? 'success' : ($isError ? 'warning' : 'active');
                @endphp
                <div class="status-number {{ $class }}">
                    {{ $icon }}
                </div>
                <div>
                    <strong>{{ $spj->nomor_spj }}</strong>
                    <span>
                        @if($spj->status == 'draft')
                            Disimpan sebagai draft
                        @elseif($spj->status == 'diajukan')
                            Telah diajukan ke Umum
                        @elseif($spj->status == 'disetujui_umum')
                            Telah disetujui Umum (Menunggu PPK)
                        @elseif($spj->status == 'disetujui_ppk')
                            Telah disetujui PPK (Menunggu PPSPM)
                        @elseif($spj->status == 'disetujui_ppspm')
                            Telah disetujui PPSPM (Proses Bendahara)
                        @elseif(str_contains($spj->status, 'revisi'))
                            Dikembalikan oleh {{ ucfirst(explode('_', $spj->status)[1] ?? 'Pemeriksa') }}
                        @elseif($spj->status == 'selesai')
                            Selesai & Diarsipkan
                        @endif
                    </span>
                </div>
            </div>
            @empty
            <div style="padding: 1rem; color: #6b7280; text-align: center;">Belum ada status SPJ terbaru.</div>
            @endforelse

        </div>

    </div>

</section>



<section class="panel workflow-panel">
    @if($recentSpjs->isNotEmpty())
    @php $topSpj = $recentSpjs->first(); @endphp
    <div class="panel-header">
        <div>
            <h2>Alur {{ $topSpj->nomor_spj }}</h2>
            <p>Posisi pengajuan terakhir Anda</p>
        </div>
        <a href="{{ route('teknis.spj.show', $topSpj->id) }}">Detail →</a>
    </div>

    <div class="workflow">
        @php
            $isUmum = in_array($topSpj->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'selesai']);
            $isPpk = in_array($topSpj->status, ['disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'selesai']);
            $isPpspm = in_array($topSpj->status, ['disetujui_ppk', 'disetujui_ppspm', 'selesai']);
            $isBendahara = in_array($topSpj->status, ['disetujui_ppspm', 'selesai']);
            $isDone = $topSpj->status == 'selesai';
        @endphp
        <!-- TEKNIS -->
        <div class="workflow-step completed">
            <div class="workflow-circle">✓</div>
            <strong>Teknis</strong>
            <small>Pengajuan</small>
        </div>

        <div class="workflow-line completed"></div>

        <!-- UMUM -->
        <div class="workflow-step {{ $isUmum && !$isPpk ? 'current' : '' }} {{ $isPpk ? 'completed' : '' }}">
            <div class="workflow-circle">{{ $isPpk ? '✓' : '2' }}</div>
            <strong>Umum</strong>
            <small>Pemeriksaan</small>
        </div>

        <div class="workflow-line {{ $isPpk ? 'completed' : '' }}"></div>

        <!-- PPK -->
        <div class="workflow-step {{ $isPpk && !$isPpspm ? 'current' : '' }} {{ $isPpspm ? 'completed' : '' }}">
            <div class="workflow-circle">{{ $isPpspm ? '✓' : '3' }}</div>
            <strong>PPK</strong>
            <small>Persetujuan</small>
        </div>

        <div class="workflow-line {{ $isPpspm ? 'completed' : '' }}"></div>

        <!-- PPSPM -->
        <div class="workflow-step {{ $isPpspm && !$isBendahara ? 'current' : '' }} {{ $isBendahara ? 'completed' : '' }}">
            <div class="workflow-circle">{{ $isBendahara ? '✓' : '4' }}</div>
            <strong>PPSPM</strong>
            <small>Verifikasi</small>
        </div>

        <div class="workflow-line {{ $isBendahara ? 'completed' : '' }}"></div>

        <!-- BENDAHARA -->
        <div class="workflow-step {{ $isBendahara && !$isDone ? 'current' : '' }} {{ $isDone ? 'completed' : '' }}">
            <div class="workflow-circle">{{ $isDone ? '✓' : '5' }}</div>
            <strong>Bendahara</strong>
            <small>Pembayaran</small>
        </div>

        <div class="workflow-line {{ $isDone ? 'completed' : '' }}"></div>

        <!-- SELESAI -->
        <div class="workflow-step {{ $isDone ? 'current completed' : '' }}">
            <div class="workflow-circle">{{ $isDone ? '✓' : '6' }}</div>
            <strong>Arsip</strong>
            <small>Selesai</small>
        </div>
    </div>
    @else
    <div class="panel-header">
        <div>
            <h2>Alur SPJ</h2>
            <p>Belum ada SPJ untuk ditampilkan alurnya.</p>
        </div>
    </div>
    @endif
</section>



<!-- NOTIFIKASI -->

<section class="panel notification-panel">

    <div class="panel-header">

        <div>

            <h2>
                Perlu Perhatian
            </h2>

            <p>
                Informasi mengenai SPJ Anda
            </p>

        </div>

    </div>


    <div class="attention-list">

        @php
            $needAttention = $recentSpjs->filter(function($spj) {
                return str_contains($spj->status, 'revisi') || in_array($spj->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm']);
            });
        @endphp

        @forelse($needAttention->take(3) as $spj)
            @if(str_contains($spj->status, 'revisi'))
                <div class="attention-item warning">
                    <div class="attention-icon">!</div>
                    <div>
                        <strong>{{ $spj->nomor_spj }} dikembalikan oleh {{ strtoupper(explode('_', $spj->status)[1] ?? '') }}</strong>
                        <p>{{ Str::limit($spj->catatan_revisi, 50, '...') }}</p>
                    </div>
                    <a href="{{ route('teknis.spj.edit', $spj->id) }}">Perbaiki →</a>
                </div>
            @elseif(in_array($spj->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm']))
                <div class="attention-item info">
                    <div class="attention-icon">i</div>
                    <div>
                        <strong>{{ $spj->nomor_spj }} sedang diproses</strong>
                        <p>Pengajuan Anda sedang di tahap {{ str_replace('_', ' ', $spj->status) }}.</p>
                    </div>
                </div>
            @endif
        @empty
            <div style="padding: 1rem; color: #6b7280; text-align: center;">Tidak ada pemberitahuan saat ini.</div>
        @endforelse

    </div>

</section>


@endsection
