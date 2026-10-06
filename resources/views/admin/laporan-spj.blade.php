@extends('layouts.admin')

@php
$title = 'Laporan SPJ';
$subtitle = 'Rekap dan laporan data SPJ.';
@endphp

@section('content')

<div class="laporan-page">

    {{-- HEADER --}}
    <div class="page-header">

        <div>
            <h2>Laporan SPJ</h2>

            <p>
                Rekapitulasi data SPJ berdasarkan periode dan status.
            </p>
        </div>

        <button
            type="button"
            class="btn-print"
            onclick="window.print()">

            <i class="bi bi-printer"></i>

            Cetak Laporan

        </button>

    </div>


    {{-- FILTER --}}
    <div class="laporan-filter">

        <form
            action="{{ route('admin.laporan.spj') }}"
            method="GET">

            <div class="filter-group">

                <label>
                    Tanggal Mulai
                </label>

                <input
                    type="date"
                    name="tanggal_mulai"
                    value="{{ request('tanggal_mulai') }}">

            </div>


            <div class="filter-group">

                <label>
                    Tanggal Selesai
                </label>

                <input
                    type="date"
                    name="tanggal_selesai"
                    value="{{ request('tanggal_selesai') }}">

            </div>


            <div class="filter-group">

                <label>
                    Status
                </label>

                <select name="status">

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="diajukan"
                        {{ request('status') == 'diajukan' ? 'selected' : '' }}>
                        Diajukan
                    </option>

                    <option
                        value="diproses"
                        {{ request('status') == 'diproses' ? 'selected' : '' }}>
                        Diproses
                    </option>

                    <option
                        value="selesai"
                        {{ request('status') == 'selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>

                    <option
                        value="dikembalikan"
                        {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>
                        Dikembalikan
                    </option>

                    <option
                        value="draft"
                        {{ request('status') == 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>

                </select>

            </div>


            <div class="filter-buttons">
                <a
                    href="{{ route('admin.laporan.spj') }}"
                    class="btn-reset">

                    Reset

                </a>

            </div>

        </form>

    </div>


    {{-- RINGKASAN --}}
    <div class="laporan-stats">

        <div class="laporan-stat-card">

            <div class="laporan-stat-icon blue">
                <i class="bi bi-file-earmark-text"></i>
            </div>

            <div>

                <span>Total SPJ</span>

                <strong>
                    {{ $totalSpj }}
                </strong>

                <small>
                    Data laporan
                </small>

            </div>

        </div>


        <div class="laporan-stat-card">

            <div class="laporan-stat-icon orange">
                <i class="bi bi-send"></i>
            </div>

            <div>

                <span>Diajukan</span>

                <strong>
                    {{ $totalDiajukan }}
                </strong>

                <small>
                    Menunggu proses
                </small>

            </div>

        </div>


        <div class="laporan-stat-card">

            <div class="laporan-stat-icon purple">
                <i class="bi bi-arrow-repeat"></i>
            </div>

            <div>

                <span>Diproses</span>

                <strong>
                    {{ $totalDiproses }}
                </strong>

                <small>
                    Sedang diperiksa
                </small>

            </div>

        </div>


        <div class="laporan-stat-card">

            <div class="laporan-stat-icon green">
                <i class="bi bi-check-circle"></i>
            </div>

            <div>

                <span>Selesai</span>

                <strong>
                    {{ $totalSelesai }}
                </strong>

                <small>
                    SPJ selesai
                </small>

            </div>

        </div>


        <div class="laporan-stat-card">

            <div class="laporan-stat-icon red">
                <i class="bi bi-arrow-return-left"></i>
            </div>

            <div>

                <span>Dikembalikan</span>

                <strong>
                    {{ $totalDikembalikan }}
                </strong>

                <small>
                    Perlu diperbaiki
                </small>

            </div>

        </div>

    </div>

    {{-- TABEL --}}
    <div class="laporan-panel">

        <div class="panel-header">

            <div>

                <h3>
                    Daftar Laporan SPJ
                </h3>

                <p>
                    Data SPJ berdasarkan filter yang dipilih.
                </p>

            </div>

            <span class="jumlah-data">

                {{ $totalSpj }} data

            </span>

        </div>


        <div class="table-wrapper">

            <table class="laporan-table">

                <thead>

                    <tr>

                        <th>
                            No
                        </th>

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

                    @forelse($spjs as $index => $spj)

                    <tr>

                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>

                            <strong>
                                {{ $spj->nomor_spj }}
                            </strong>

                        </td>

                        <td>

                            {{ $spj->tanggal
                                ? \Carbon\Carbon::parse($spj->tanggal)->format('d/m/Y')
                                : '-'
                            }}

                        </td>

                        <td>
                            {{ $spj->kegiatan }}
                        </td>

                        <td>

                            Rp
                            {{ number_format($spj->nilai, 0, ',', '.') }}

                        </td>

                        <td>

                            @if($spj->status === 'diajukan')

                            <span class="laporan-badge pending">
                                Diajukan
                            </span>

                            @elseif($spj->status === 'diproses')

                            <span class="laporan-badge processing">
                                Diproses
                            </span>

                            @elseif($spj->status === 'selesai')

                            <span class="laporan-badge completed">
                                Selesai
                            </span>

                            @elseif($spj->status === 'dikembalikan')

                            <span class="laporan-badge returned">
                                Dikembalikan
                            </span>

                            @elseif($spj->status === 'draft')

                            <span class="laporan-badge draft">
                                Draft
                            </span>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="6"
                            class="empty-data">

                            <i class="bi bi-inbox"></i>

                            <strong>
                                Tidak ada data SPJ
                            </strong>

                            <span>
                                Belum ada data yang sesuai dengan filter.
                            </span>

                        </td>

                    </tr>

                    @endforelse

                </tbody>


                @if($spjs->count() > 0)

                <tfoot>

                    <tr>

                        <td
                            colspan="4"
                            class="total-label">

                            TOTAL

                        </td>

                        <td class="total-value">

                            Rp
                            {{ number_format($totalNilai, 0, ',', '.') }}

                        </td>

                        <td></td>

                    </tr>

                </tfoot>

                @endif

            </table>

        </div>

    </div>

</div>


@endsection