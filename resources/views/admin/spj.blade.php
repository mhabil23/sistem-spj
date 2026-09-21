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

        <a href="#" class="btn-primary">
            <span>+</span>
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

                    {{-- DATA 1 --}}
                    <tr>

                        <td>01</td>

                        <td>
                            <div class="spj-number">
                                <strong>SPJ-001/IX/2026</strong>
                                <span>SPJ-2026-0001</span>
                            </div>
                        </td>

                        <td>
                            <div class="spj-description">
                                Belanja perjalanan dinas
                            </div>
                        </td>

                        <td>
                            <div class="submitter">
                                <div class="avatar teknis">A</div>

                                <div>
                                    <strong>Ahmad Fauzan</strong>
                                    <span>Teknis</span>
                                </div>
                            </div>
                        </td>

                        <td>
                            21 Sep 2026
                        </td>

                        <td>
                            <strong class="amount">
                                Rp 2.500.000
                            </strong>
                        </td>

                        <td>
                            <span class="spj-status process">
                                <i></i>
                                Diproses
                            </span>
                        </td>

                        <td>
                            <div class="action-buttons">

                                <button class="action-btn view"
                                    title="Lihat">
                                    👁
                                </button>

                                <button class="action-btn edit"
                                    title="Edit">
                                    ✎
                                </button>

                            </div>
                        </td>

                    </tr>


                    {{-- DATA 2 --}}
                    <tr>

                        <td>02</td>

                        <td>
                            <div class="spj-number">
                                <strong>SPJ-002/IX/2026</strong>
                                <span>SPJ-2026-0002</span>
                            </div>
                        </td>

                        <td>
                            <div class="spj-description">
                                Belanja alat tulis kantor
                            </div>
                        </td>

                        <td>
                            <div class="submitter">
                                <div class="avatar umum">S</div>

                                <div>
                                    <strong>Siti Rahma</strong>
                                    <span>Umum / PPSPM</span>
                                </div>
                            </div>
                        </td>

                        <td>
                            20 Sep 2026
                        </td>

                        <td>
                            <strong class="amount">
                                Rp 1.250.000
                            </strong>
                        </td>

                        <td>
                            <span class="spj-status approved">
                                <i></i>
                                Disetujui
                            </span>
                        </td>

                        <td>
                            <div class="action-buttons">

                                <button class="action-btn view">
                                    👁
                                </button>

                                <button class="action-btn edit">
                                    ✎
                                </button>

                            </div>
                        </td>

                    </tr>


                    {{-- DATA 3 --}}
                    <tr>

                        <td>03</td>

                        <td>
                            <div class="spj-number">
                                <strong>SPJ-003/IX/2026</strong>
                                <span>SPJ-2026-0003</span>
                            </div>
                        </td>

                        <td>
                            <div class="spj-description">
                                Honorarium kegiatan
                            </div>
                        </td>

                        <td>
                            <div class="submitter">
                                <div class="avatar ppk">B</div>

                                <div>
                                    <strong>Budi Santoso</strong>
                                    <span>PPK</span>
                                </div>
                            </div>
                        </td>

                        <td>
                            19 Sep 2026
                        </td>

                        <td>
                            <strong class="amount">
                                Rp 3.750.000
                            </strong>
                        </td>

                        <td>
                            <span class="spj-status waiting">
                                <i></i>
                                Menunggu
                            </span>
                        </td>

                        <td>
                            <div class="action-buttons">

                                <button class="action-btn view">
                                    👁
                                </button>

                                <button class="action-btn edit">
                                    ✎
                                </button>

                            </div>
                        </td>

                    </tr>


                    {{-- DATA 4 --}}
                    <tr>

                        <td>04</td>

                        <td>
                            <div class="spj-number">
                                <strong>SPJ-004/IX/2026</strong>
                                <span>SPJ-2026-0004</span>
                            </div>
                        </td>

                        <td>
                            <div class="spj-description">
                                Pengadaan perlengkapan kantor
                            </div>
                        </td>

                        <td>
                            <div class="submitter">
                                <div class="avatar bendahara">D</div>

                                <div>
                                    <strong>Dewi Lestari</strong>
                                    <span>Bendahara</span>
                                </div>
                            </div>
                        </td>

                        <td>
                            18 Sep 2026
                        </td>

                        <td>
                            <strong class="amount">
                                Rp 5.200.000
                            </strong>
                        </td>

                        <td>
                            <span class="spj-status returned">
                                <i></i>
                                Dikembalikan
                            </span>
                        </td>

                        <td>
                            <div class="action-buttons">

                                <button class="action-btn view">
                                    👁
                                </button>

                                <button class="action-btn edit">
                                    ✎
                                </button>

                            </div>
                        </td>

                    </tr>

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