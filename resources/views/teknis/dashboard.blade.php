@extends('layouts.teknis')

@php
$title = 'Dashboard';
$subtitle = 'Pantau dan kelola pengajuan SPJ Anda.';
@endphp

@section('content')


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


    <a href="#" class="btn-primary">

        <span>+</span>

        Ajukan SPJ

    </a>

</div>



<!-- STATISTIK -->

<section class="stats-grid">


    <!-- TOTAL -->

    <div class="stat-card">

        <div class="stat-icon blue">
            ▣
        </div>

        <div class="stat-content">

            <span>
                Total SPJ
            </span>

            <strong>
                24
            </strong>

            <small>
                Semua pengajuan Anda
            </small>

        </div>

    </div>


    <!-- DIPROSES -->

    <div class="stat-card">

        <div class="stat-icon orange">
            ◷
        </div>

        <div class="stat-content">

            <span>
                Sedang Diproses
            </span>

            <strong>
                8
            </strong>

            <small>
                Menunggu pemeriksaan
            </small>

        </div>

    </div>


    <!-- DIKEMBALIKAN -->

    <div class="stat-card">

        <div class="stat-icon red">
            !
        </div>

        <div class="stat-content">

            <span>
                Dikembalikan
            </span>

            <strong>
                3
            </strong>

            <small>
                Perlu diperbaiki
            </small>

        </div>

    </div>


    <!-- SELESAI -->

    <div class="stat-card">

        <div class="stat-icon green">
            ✓
        </div>

        <div class="stat-content">

            <span>
                Selesai
            </span>

            <strong>
                13
            </strong>

            <small>
                SPJ telah selesai
            </small>

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

            <a href="#">
                Lihat semua →
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


                    <tr>

                        <td>

                            <strong>
                                SPJ-2026-001
                            </strong>

                        </td>

                        <td>
                            Perjalanan Dinas
                        </td>

                        <td>
                            Rp 5.000.000
                        </td>

                        <td>
                            Umum/PPSPM
                        </td>

                        <td>

                            <span class="badge pending">
                                Diproses
                            </span>

                        </td>

                    </tr>


                    <tr>

                        <td>

                            <strong>
                                SPJ-2026-002
                            </strong>

                        </td>

                        <td>
                            Kegiatan Rapat
                        </td>

                        <td>
                            Rp 2.500.000
                        </td>

                        <td>
                            PPK
                        </td>

                        <td>

                            <span class="badge approved">
                                Disetujui
                            </span>

                        </td>

                    </tr>


                    <tr>

                        <td>

                            <strong>
                                SPJ-2026-003
                            </strong>

                        </td>

                        <td>
                            Pengadaan ATK
                        </td>

                        <td>
                            Rp 1.750.000
                        </td>

                        <td>
                            Teknis
                        </td>

                        <td>

                            <span class="badge returned">
                                Dikembalikan
                            </span>

                        </td>

                    </tr>


                    <tr>

                        <td>

                            <strong>
                                SPJ-2026-004
                            </strong>

                        </td>

                        <td>
                            Perjalanan Dinas
                        </td>

                        <td>
                            Rp 4.200.000
                        </td>

                        <td>
                            Arsip
                        </td>

                        <td>

                            <span class="badge completed">
                                Selesai
                            </span>

                        </td>

                    </tr>


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


            <div class="status-item">

                <div class="status-number active">
                    ✓
                </div>

                <div>

                    <strong>
                        SPJ-2026-001
                    </strong>

                    <span>
                        Sedang diperiksa Umum/PPSPM
                    </span>

                </div>

            </div>


            <div class="status-item">

                <div class="status-number success">
                    ✓
                </div>

                <div>

                    <strong>
                        SPJ-2026-002
                    </strong>

                    <span>
                        Telah disetujui PPK
                    </span>

                </div>

            </div>


            <div class="status-item">

                <div class="status-number warning">
                    !
                </div>

                <div>

                    <strong>
                        SPJ-2026-003
                    </strong>

                    <span>
                        Dokumen perlu diperbaiki
                    </span>

                </div>

            </div>


        </div>

    </div>

</section>



<!-- ALUR SPJ -->

<section class="panel workflow-panel">

    <div class="panel-header">

        <div>

            <h2>
                Alur SPJ-2026-001
            </h2>

            <p>
                Posisi pengajuan saat ini
            </p>

        </div>

        <a href="#">
            Detail →
        </a>

    </div>


    <div class="workflow">


        <!-- TEKNIS -->

        <div class="workflow-step completed">

            <div class="workflow-circle">
                ✓
            </div>

            <strong>
                Teknis
            </strong>

            <small>
                Pengajuan
            </small>

        </div>


        <div class="workflow-line completed">
        </div>


        <!-- UMUM -->

        <div class="workflow-step current">

            <div class="workflow-circle">
                2
            </div>

            <strong>
                Umum/PPSPM
            </strong>

            <small>
                Pemeriksaan
            </small>

        </div>


        <div class="workflow-line">
        </div>


        <!-- PPK -->

        <div class="workflow-step">

            <div class="workflow-circle">
                3
            </div>

            <strong>
                PPK
            </strong>

            <small>
                Persetujuan
            </small>

        </div>


        <div class="workflow-line">
        </div>


        <!-- BENDAHARA -->

        <div class="workflow-step">

            <div class="workflow-circle">
                4
            </div>

            <strong>
                Bendahara
            </strong>

            <small>
                Pembayaran
            </small>

        </div>


        <div class="workflow-line">
        </div>


        <!-- SELESAI -->

        <div class="workflow-step">

            <div class="workflow-circle">
                ✓
            </div>

            <strong>
                Arsip
            </strong>

            <small>
                Selesai
            </small>

        </div>

    </div>

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


        <div class="attention-item warning">

            <div class="attention-icon">
                !
            </div>

            <div>

                <strong>
                    SPJ-2026-003 perlu diperbaiki
                </strong>

                <p>
                    Dokumen kuitansi belum lengkap.
                    Silakan perbaiki dan kirim kembali.
                </p>

            </div>

            <a href="#">
                Perbaiki →
            </a>

        </div>


        <div class="attention-item info">

            <div class="attention-icon">
                i
            </div>

            <div>

                <strong>
                    SPJ-2026-001 sedang diperiksa
                </strong>

                <p>
                    SPJ sedang diperiksa oleh
                    Subbagian Umum/PPSPM.
                </p>

            </div>

        </div>

    </div>

</section>


@endsection