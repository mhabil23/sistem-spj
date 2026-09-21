@extends('layouts.admin')

@section('content')

<div class="history-page">

    {{-- HEADER --}}
    <div class="history-header">

        <div>
            <h2>Riwayat Proses</h2>

            <p>
                Pantau seluruh riwayat proses dokumen SPJ.
            </p>
        </div>

        <button class="btn-filter">
            <span>⚙</span>
            Filter
        </button>

    </div>


    {{-- STATISTIK --}}
    <div class="history-stats">

        <div class="history-stat-card">

            <div class="history-stat-icon blue">
                ↻
            </div>

            <div>
                <span>Total Proses</span>
                <strong>128</strong>
                <small>Seluruh proses SPJ</small>
            </div>

        </div>


        <div class="history-stat-card">

            <div class="history-stat-icon orange">
                ◷
            </div>

            <div>
                <span>Sedang Diproses</span>
                <strong>18</strong>
                <small>Menunggu proses</small>
            </div>

        </div>


        <div class="history-stat-card">

            <div class="history-stat-icon green">
                ✓
            </div>

            <div>
                <span>Selesai</span>
                <strong>96</strong>
                <small>Proses selesai</small>
            </div>

        </div>


        <div class="history-stat-card">

            <div class="history-stat-icon red">
                !
            </div>

            <div>
                <span>Dikembalikan</span>
                <strong>14</strong>
                <small>Perlu diperbaiki</small>
            </div>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="history-panel">

        <div class="history-toolbar">

            <div>
                <h3>Riwayat Aktivitas</h3>

                <p>
                    Daftar aktivitas proses SPJ terbaru.
                </p>
            </div>


            <div class="history-actions">

                <div class="history-search">

                    <span>⌕</span>

                    <input
                        type="text"
                        placeholder="Cari dokumen...">

                </div>

                <select class="history-select">

                    <option>Semua Status</option>
                    <option>Diproses</option>
                    <option>Selesai</option>
                    <option>Dikembalikan</option>

                </select>

            </div>

        </div>


        <div class="history-table-wrapper">

            <table class="history-table">

                <thead>

                    <tr>

                        <th>DOKUMEN</th>
                        <th>AKTIVITAS</th>
                        <th>PENGGUNA</th>
                        <th>STATUS</th>
                        <th>WAKTU</th>
                        <th>AKSI</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td>

                            <div class="document-info">

                                <div class="document-icon">
                                    PDF
                                </div>

                                <div>
                                    <strong>
                                        SPJ Perjalanan Dinas
                                    </strong>

                                    <span>
                                        SPJ-2026-001
                                    </span>
                                </div>

                            </div>

                        </td>


                        <td>

                            <div class="activity">

                                <strong>
                                    Pengajuan SPJ
                                </strong>

                                <span>
                                    Dokumen diajukan
                                </span>

                            </div>

                        </td>


                        <td>

                            <div class="user-info">

                                <div class="user-avatar">
                                    AH
                                </div>

                                <span>
                                    Ahmad
                                </span>

                            </div>

                        </td>


                        <td>

                            <span class="history-status processing">
                                <i></i>
                                Diproses
                            </span>

                        </td>


                        <td>

                            <div class="time-info">

                                <strong>
                                    21 Sep 2026
                                </strong>

                                <span>
                                    08:45 WIB
                                </span>

                            </div>

                        </td>


                        <td>

                            <button class="history-action">
                                →
                            </button>

                        </td>

                    </tr>


                    <tr>

                        <td>

                            <div class="document-info">

                                <div class="document-icon">
                                    PDF
                                </div>

                                <div>

                                    <strong>
                                        SPJ Belanja Barang
                                    </strong>

                                    <span>
                                        SPJ-2026-002
                                    </span>

                                </div>

                            </div>

                        </td>


                        <td>

                            <div class="activity">

                                <strong>
                                    Verifikasi SPJ
                                </strong>

                                <span>
                                    Dokumen diverifikasi
                                </span>

                            </div>

                        </td>


                        <td>

                            <div class="user-info">

                                <div class="user-avatar green">
                                    BS
                                </div>

                                <span>
                                    Budi
                                </span>

                            </div>

                        </td>


                        <td>

                            <span class="history-status completed">
                                <i></i>
                                Selesai
                            </span>

                        </td>


                        <td>

                            <div class="time-info">

                                <strong>
                                    20 Sep 2026
                                </strong>

                                <span>
                                    15:20 WIB
                                </span>

                            </div>

                        </td>


                        <td>

                            <button class="history-action">
                                →
                            </button>

                        </td>

                    </tr>


                    <tr>

                        <td>

                            <div class="document-info">

                                <div class="document-icon">
                                    PDF
                                </div>

                                <div>

                                    <strong>
                                        SPJ Honorarium
                                    </strong>

                                    <span>
                                        SPJ-2026-003
                                    </span>

                                </div>

                            </div>

                        </td>


                        <td>

                            <div class="activity">

                                <strong>
                                    Revisi SPJ
                                </strong>

                                <span>
                                    Dokumen dikembalikan
                                </span>

                            </div>

                        </td>


                        <td>

                            <div class="user-info">

                                <div class="user-avatar purple">
                                    RN
                                </div>

                                <span>
                                    Rina
                                </span>

                            </div>

                        </td>


                        <td>

                            <span class="history-status returned">
                                <i></i>
                                Dikembalikan
                            </span>

                        </td>


                        <td>

                            <div class="time-info">

                                <strong>
                                    20 Sep 2026
                                </strong>

                                <span>
                                    10:15 WIB
                                </span>

                            </div>

                        </td>


                        <td>

                            <button class="history-action">
                                →
                            </button>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        {{-- FOOTER --}}
        <div class="history-footer">

            <span>
                Menampilkan <strong>3</strong> dari
                <strong>128</strong> riwayat
            </span>


            <div class="pagination">

                <button disabled>
                    ‹
                </button>

                <button class="active">
                    1
                </button>

                <button>
                    2
                </button>

                <button>
                    3
                </button>

                <button>
                    ...
                </button>

                <button>
                    10
                </button>

                <button>
                    ›
                </button>

            </div>

        </div>

    </div>

</div>

@endsection