@extends('layouts.umum')

@php

$title = 'Dashboard';

$subtitle = 'Kelola dan periksa SPJ yang masuk dari bagian Teknis.';

@endphp


@section('content')

<div class="dashboard-page">

    <!-- HEADER -->
    <div class="dashboard-header">

        <div>
            <h2>
                Selamat Datang, Pengguna Umum
            </h2>

            <p>
                Berikut adalah ringkasan SPJ yang perlu Anda periksa.
            </p>
        </div>

        <div class="header-date">
            <span>Hari ini</span>
            <strong>{{ date('d F Y') }}</strong>
        </div>

    </div>


    <!-- STATISTIK -->
    <div class="stats-grid">

        <div class="stat-card">

            <div class="stat-icon blue">
                ▣
            </div>

            <div class="stat-info">
                <span>Total SPJ Masuk</span>
                <strong>24</strong>
                <small>Seluruh pengajuan</small>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon orange">
                ◷
            </div>

            <div class="stat-info">
                <span>Menunggu Pemeriksaan</span>
                <strong>5</strong>
                <small>Perlu segera diperiksa</small>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon red">
                ↩
            </div>

            <div class="stat-info">
                <span>Dikembalikan</span>
                <strong>2</strong>
                <small>Menunggu perbaikan</small>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon green">
                ✓
            </div>

            <div class="stat-info">
                <span>Selesai Diperiksa</span>
                <strong>17</strong>
                <small>Telah diteruskan</small>
            </div>

        </div>

    </div>


    <!-- MAIN GRID -->
    <div class="dashboard-grid">


        <!-- SPJ MASUK -->
        <section class="panel spj-panel">

            <div class="panel-header">

                <div>
                    <h3>SPJ Menunggu Pemeriksaan</h3>

                    <p>
                        Daftar SPJ yang dikirim oleh bagian Teknis.
                    </p>
                </div>

                <a href="#" class="view-all">
                    Lihat Semua →
                </a>

            </div>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>
                            <th>No. SPJ</th>
                            <th>Pengajuan</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td>
                                <strong>SPJ-2026-001</strong>
                            </td>

                            <td>
                                Perjalanan Dinas
                            </td>

                            <td>
                                20 Sep 2026
                            </td>

                            <td>
                                Rp5.000.000
                            </td>

                            <td>
                                <span class="status pending">
                                    Menunggu
                                </span>
                            </td>

                            <td>
                                <a href="#" class="btn-view">
                                    Periksa
                                </a>
                            </td>

                        </tr>


                        <tr>

                            <td>
                                <strong>SPJ-2026-005</strong>
                            </td>

                            <td>
                                Kegiatan Rapat
                            </td>

                            <td>
                                20 Sep 2026
                            </td>

                            <td>
                                Rp2.500.000
                            </td>

                            <td>
                                <span class="status pending">
                                    Menunggu
                                </span>
                            </td>

                            <td>
                                <a href="#" class="btn-view">
                                    Periksa
                                </a>
                            </td>

                        </tr>


                        <tr>

                            <td>
                                <strong>SPJ-2026-006</strong>
                            </td>

                            <td>
                                Pengadaan ATK
                            </td>

                            <td>
                                19 Sep 2026
                            </td>

                            <td>
                                Rp1.750.000
                            </td>

                            <td>
                                <span class="status pending">
                                    Menunggu
                                </span>
                            </td>

                            <td>
                                <a href="#" class="btn-view">
                                    Periksa
                                </a>
                            </td>

                        </tr>


                        <tr>

                            <td>
                                <strong>SPJ-2026-007</strong>
                            </td>

                            <td>
                                Perjalanan Dinas
                            </td>

                            <td>
                                19 Sep 2026
                            </td>

                            <td>
                                Rp4.200.000
                            </td>

                            <td>
                                <span class="status pending">
                                    Menunggu
                                </span>
                            </td>

                            <td>
                                <a href="#" class="btn-view">
                                    Periksa
                                </a>
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </section>


        <!-- STATUS -->
        <section class="panel status-panel">

            <div class="panel-header">

                <div>
                    <h3>Status Pemeriksaan</h3>
                    <p>Ringkasan pekerjaan Anda.</p>
                </div>

            </div>


            <div class="status-list">

                <div class="status-item">

                    <div class="status-item-icon orange">
                        ◷
                    </div>

                    <div>
                        <strong>5 SPJ</strong>
                        <span>Menunggu diperiksa</span>
                    </div>

                </div>


                <div class="status-item">

                    <div class="status-item-icon red">
                        ↩
                    </div>

                    <div>
                        <strong>2 SPJ</strong>
                        <span>Dikembalikan ke Teknis</span>
                    </div>

                </div>


                <div class="status-item">

                    <div class="status-item-icon green">
                        ✓
                    </div>

                    <div>
                        <strong>17 SPJ</strong>
                        <span>Telah diteruskan ke PPK</span>
                    </div>

                </div>

            </div>

        </section>

    </div>


    <!-- ALUR SPJ -->
    <section class="panel workflow-panel">

        <div class="panel-header">

            <div>
                <h3>Monitoring Alur SPJ</h3>

                <p>
                    Status perjalanan SPJ terbaru.
                </p>
            </div>

            <span class="workflow-number">
                SPJ-2026-001
            </span>

        </div>


        <div class="workflow">

            <div class="workflow-step completed">

                <div class="workflow-circle">
                    ✓
                </div>

                <div class="workflow-content">

                    <strong>Teknis</strong>

                    <span>
                        SPJ diajukan
                    </span>

                </div>

            </div>


            <div class="workflow-line completed-line"></div>


            <div class="workflow-step active">

                <div class="workflow-circle">
                    2
                </div>

                <div class="workflow-content">

                    <strong>Umum / PPSPM</strong>

                    <span>
                        Sedang diperiksa
                    </span>

                </div>

            </div>


            <div class="workflow-line"></div>


            <div class="workflow-step">

                <div class="workflow-circle">
                    3
                </div>

                <div class="workflow-content">

                    <strong>PPK</strong>

                    <span>
                        Menunggu
                    </span>

                </div>

            </div>


            <div class="workflow-line"></div>


            <div class="workflow-step">

                <div class="workflow-circle">
                    4
                </div>

                <div class="workflow-content">

                    <strong>Bendahara</strong>

                    <span>
                        Menunggu
                    </span>

                </div>

            </div>


            <div class="workflow-line"></div>


            <div class="workflow-step">

                <div class="workflow-circle">
                    5
                </div>

                <div class="workflow-content">

                    <strong>Arsip</strong>

                    <span>
                        Menunggu
                    </span>

                </div>

            </div>

        </div>

    </section>


    <!-- PERLU PERHATIAN -->
    <section class="panel attention-panel">

        <div class="panel-header">

            <div>
                <h3>Perlu Perhatian</h3>

                <p>
                    SPJ yang membutuhkan tindakan.
                </p>
            </div>

        </div>


        <div class="attention-list">

            <div class="attention-item warning">

                <div class="attention-icon">
                    !
                </div>

                <div class="attention-info">

                    <strong>
                        5 SPJ menunggu pemeriksaan
                    </strong>

                    <p>
                        Segera lakukan pemeriksaan agar proses SPJ
                        tidak tertunda.
                    </p>

                </div>

                <a href="#">
                    Periksa →
                </a>

            </div>


            <div class="attention-item danger">

                <div class="attention-icon">
                    !
                </div>

                <div class="attention-info">

                    <strong>
                        2 SPJ dikembalikan
                    </strong>

                    <p>
                        Pastikan catatan perbaikan telah diteruskan
                        kepada bagian Teknis.
                    </p>

                </div>

                <a href="#">
                    Lihat →
                </a>

            </div>

        </div>

    </section>

</div>

@endsection