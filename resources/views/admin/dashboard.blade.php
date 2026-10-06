@extends('layouts.admin')


@section('content')


<!-- STATISTICS -->

<section class="stats-grid">

    {{-- TOTAL SPJ --}}
    <div class="stat-card">

        <div class="stat-icon blue">
            ▣
        </div>

        <div class="stat-content">

            <span>Total SPJ</span>

            <strong>{{ $totalSpj }}</strong>

            <small>
                Seluruh SPJ terdaftar
            </small>

        </div>

    </div>


    {{-- MENUNGGU PEMERIKSAAN --}}
    <div class="stat-card">

        <div class="stat-icon orange">
            ◷
        </div>

        <div class="stat-content">

            <span>Menunggu Pemeriksaan</span>

            <strong>{{ $menungguProses }}</strong>

            <small>
                Perlu ditindaklanjuti
            </small>

        </div>

    </div>


    {{-- SPJ SELESAI --}}
    <div class="stat-card">

        <div class="stat-icon green">
            ✓
        </div>

        <div class="stat-content">

            <span>SPJ Selesai</span>

            <strong>{{ $selesai }}</strong>

            <small>
                SPJ telah selesai
            </small>

        </div>

    </div>


    {{-- TOTAL PENGGUNA --}}
    <div class="stat-card">

        <div class="stat-icon purple">
            ♙
        </div>

        <div class="stat-content">

            <span>Total Pengguna</span>

            <strong>{{ $totalPengguna }}</strong>

            <small>
                {{ $penggunaAktif }} pengguna aktif
            </small>

        </div>

    </div>

</section>



<!-- DASHBOARD GRID -->

<section class="dashboard-grid">


    <!-- SPJ TERBARU -->

    <div class="panel">

        <div class="panel-header">

            <div>

                <h2>
                    SPJ Terbaru
                </h2>

                <p>
                    Daftar pengajuan SPJ terbaru
                </p>

            </div>

            <a href="{{ route('admin.spj.index') }}">
                Lihat semua
            </a>

        </div>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>
                            Nomor SPJ
                        </th>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            Kegiatan
                        </th>

                        <th>
                            Nilai
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($spjTerbaru as $spj)

                    <tr>

                        {{-- NOMOR SPJ --}}
                        <td>
                            <strong>
                                {{ $spj->nomor_spj }}
                            </strong>
                        </td>


                        <td>
                            {{ $spj->tanggal
        ? \Carbon\Carbon::parse($spj->tanggal)->format('d M Y')
        : '-' }}
                        </td>


                        {{-- KEGIATAN --}}
                        <td>
                            {{ $spj->kegiatan }}
                        </td>


                        {{-- NILAI --}}
                        <td>
                            Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if($spj->status === 'diproses')

                            <span class="badge pending">
                                Diproses
                            </span>

                            @elseif($spj->status === 'selesai')

                            <span class="badge completed">
                                Selesai
                            </span>

                            @elseif($spj->status === 'dikembalikan')

                            <span class="badge returned">
                                Dikembalikan
                            </span>

                            @elseif($spj->status === 'diajukan')

                            <span class="badge pending">
                                Diajukan
                            </span>

                            @elseif($spj->status === 'draft')

                            <span class="badge">
                                Draft
                            </span>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" style="text-align: center;">
                            Belum ada data SPJ.
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>



    <!-- AKTIVITAS -->

    <div class="panel activity-panel">

        <div class="panel-header">

            <div>

                <h2>
                    Aktivitas Terbaru
                </h2>

                <p>
                    Aktivitas sistem
                </p>

            </div>

        </div>


        <div class="activity-list">

            @forelse($spjTerbaru as $spj)

            <div class="activity">

                {{-- ICON --}}
                <div class="activity-icon
                @if($spj->status === 'selesai')
                    green
                @elseif($spj->status === 'dikembalikan')
                    orange
                @elseif($spj->status === 'diajukan')
                    blue
                @else
                    purple
                @endif
            ">

                    @if($spj->status === 'selesai')
                    ✓
                    @elseif($spj->status === 'dikembalikan')
                    !
                    @elseif($spj->status === 'diajukan')
                    +
                    @else
                    ◷
                    @endif

                </div>


                {{-- INFORMASI AKTIVITAS --}}
                <div>

                    <strong>

                        @if($spj->status === 'selesai')

                        {{ $spj->nomor_spj }} selesai

                        @elseif($spj->status === 'dikembalikan')

                        {{ $spj->nomor_spj }} dikembalikan

                        @elseif($spj->status === 'diajukan')

                        {{ $spj->nomor_spj }} diajukan

                        @elseif($spj->status === 'diproses')

                        {{ $spj->nomor_spj }} sedang diproses

                        @else

                        {{ $spj->nomor_spj }} dibuat

                        @endif

                    </strong>


                    <span>
                        {{ $spj->kegiatan }}
                    </span>


                    <small>

                        {{ $spj->created_at
                        ? $spj->created_at->diffForHumans()
                        : '-' }}

                    </small>

                </div>

            </div>

            @empty

            <div class="activity">

                <div class="activity-icon blue">
                    ✓
                </div>

                <div>

                    <strong>
                        Belum ada aktivitas
                    </strong>

                    <span>
                        Belum ada data SPJ dalam sistem.
                    </span>

                    <small>
                        -
                    </small>

                </div>

            </div>

            @endforelse

        </div>

    </div>

</section>



<!-- WORKFLOW -->

<section class="panel workflow-panel">

    <div class="panel-header">

        <div>

            <h2>
                Monitoring Alur SPJ
            </h2>

            <p>
                Posisi SPJ berdasarkan tahapan proses
            </p>

        </div>

        <a href="{{ route('admin.spj.index') }}">
            Detail →
        </a>

    </div>


    <div class="workflow-stats">

        {{-- TEKNIS --}}
        <div class="workflow-box">

            <div class="workflow-number">
                {{ $pengajuan }}
            </div>

            <span>
                Teknis
            </span>

            <small>
                Pengajuan
            </small>

        </div>


        <div class="workflow-arrow">
            →
        </div>


        {{-- UMUM / PPSPM --}}
        <div class="workflow-box">

            <div class="workflow-number">
                {{ $pemeriksaan }}
            </div>

            <span>
                Umum/PPSPM
            </span>

            <small>
                Pemeriksaan
            </small>

        </div>


        <div class="workflow-arrow">
            →
        </div>


        {{-- PPK --}}
        <div class="workflow-box">

            <div class="workflow-number">
                {{ $persetujuan }}
            </div>

            <span>
                PPK
            </span>

            <small>
                Persetujuan
            </small>

        </div>


        <div class="workflow-arrow">
            →
        </div>


        {{-- BENDAHARA --}}
        <div class="workflow-box">

            <div class="workflow-number">
                {{ $pembayaran }}
            </div>

            <span>
                Bendahara
            </span>

            <small>
                Pembayaran
            </small>

        </div>


        <div class="workflow-arrow">
            →
        </div>


        {{-- SELESAI --}}
        <div class="workflow-box completed-box">

            <div class="workflow-number">
                {{ $selesai }}
            </div>

            <span>
                Selesai
            </span>

            <small>
                Diarsipkan
            </small>

        </div>

    </div>

</section>


@endsection