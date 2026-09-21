@extends('layouts.admin')

@section('title', 'Data SPJ')

@section('content')

<div class="spj-page">

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h2>Data SPJ</h2>
            <p>Kelola seluruh dokumen SPJ yang terdaftar dalam sistem.</p>
        </div>

        <a
            href="{{ route('admin.spj.create') }}"
            class="btn-primary">
            <span>＋</span>
            Tambah SPJ
        </a>
    </div>


    {{-- STATISTIK --}}
    <div class="spj-stats">

        <div class="spj-stat-card">
            <div class="spj-stat-icon blue">▣</div>

            <div>
                <span>Total SPJ</span>
                <strong>128</strong>
                <small>Seluruh dokumen</small>
            </div>
        </div>

        <div class="spj-stat-card">
            <div class="spj-stat-icon orange">◷</div>

            <div>
                <span>Menunggu Proses</span>
                <strong>18</strong>
                <small>Perlu diproses</small>
            </div>
        </div>

        <div class="spj-stat-card">
            <div class="spj-stat-icon green">✓</div>

            <div>
                <span>Selesai</span>
                <strong>96</strong>
                <small>Sudah selesai</small>
            </div>
        </div>

        <div class="spj-stat-card">
            <div class="spj-stat-icon red">!</div>

            <div>
                <span>Dikembalikan</span>
                <strong>14</strong>
                <small>Perlu diperbaiki</small>
            </div>
        </div>

    </div>


    {{-- PANEL --}}
    <div class="spj-panel">

        {{-- TOOLBAR --}}
        <div class="spj-toolbar">

            <div class="toolbar-title">
                <h3>Daftar SPJ</h3>
                <p>Daftar seluruh pengajuan SPJ dalam sistem.</p>
            </div>

            <div class="toolbar-actions">

                <div class="search-box">
                    <span>⌕</span>

                    <input
                        type="text"
                        placeholder="Cari nomor SPJ atau uraian...">
                </div>

                <select class="filter-select">
                    <option>Semua Status</option>
                    <option>Menunggu</option>
                    <option>Diperiksa</option>
                    <option>Disetujui</option>
                    <option>Dikembalikan</option>
                    <option>Selesai</option>
                </select>

                <select class="filter-select">
                    <option>Semua Periode</option>
                    <option>Januari 2026</option>
                    <option>Februari 2026</option>
                    <option>Maret 2026</option>
                </select>

            </div>

        </div>


        {{-- TABLE --}}
        <div class="spj-table-wrapper">

            <table class="spj-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor SPJ</th>
                        <th>Uraian</th>
                        <th>Pengaju</th>
                        <th>Tanggal</th>
                        <th>Nilai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($spjs as $spj)

                    <tr>

                        <td>
                            <strong>
                                {{ $spj->nomor_spj }}
                            </strong>
                        </td>

                        <td>
                            {{ $spj->kegiatan }}
                        </td>

                        <td>
                            {{ $spj->tanggal->format('d M Y') }}
                        </td>

                        <td>
                            Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                        </td>

                        <td>

                            <span class="spj-status {{ $spj->status }}">
                                {{ ucfirst($spj->status) }}
                            </span>

                        </td>

                        <td>
                            {{ $spj->keterangan ?: '-' }}
                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="6" style="text-align:center; padding:30px;">
                            Belum ada data SPJ.
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- FOOTER --}}
        <div class="table-footer">

            <span>
                Menampilkan <strong>1–4</strong> dari
                <strong>128</strong> SPJ
            </span>

            <div class="pagination">

                <button disabled>‹</button>

                <button class="active">1</button>

                <button>2</button>

                <button>3</button>

                <button>4</button>

                <button>5</button>

                <button>›</button>

            </div>

        </div>

    </div>

</div>

@endsection