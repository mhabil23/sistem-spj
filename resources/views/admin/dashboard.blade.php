@extends('layouts.admin')


@section('content')


<!-- STATISTICS -->

<section class="stats-grid">

    <div class="stat-card">

        <div class="stat-icon blue">
            ▣
        </div>

        <div class="stat-content">

            <span>Total SPJ</span>

            <strong>128</strong>

            <small>
                <b>+12%</b> dari bulan lalu
            </small>

        </div>

    </div>


    <div class="stat-card">

        <div class="stat-icon orange">
            ◷
        </div>

        <div class="stat-content">

            <span>Menunggu Pemeriksaan</span>

            <strong>18</strong>

            <small>
                Perlu ditindaklanjuti
            </small>

        </div>

    </div>


    <div class="stat-card">

        <div class="stat-icon green">
            ✓
        </div>

        <div class="stat-content">

            <span>SPJ Selesai</span>

            <strong>96</strong>

            <small>
                <b>+8%</b> bulan ini
            </small>

        </div>

    </div>


    <div class="stat-card">

        <div class="stat-icon purple">
            ♙
        </div>

        <div class="stat-content">

            <span>Total Pengguna</span>

            <strong>24</strong>

            <small>
                5 role aktif
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
                            Pengaju
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

                    <tr>

                        <td>
                            <strong>
                                SPJ-2026-001
                            </strong>
                        </td>

                        <td>
                            Ahmad
                        </td>

                        <td>
                            Perjalanan Dinas
                        </td>

                        <td>
                            Rp 5.000.000
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
                            Budi
                        </td>

                        <td>
                            Kegiatan Rapat
                        </td>

                        <td>
                            Rp 2.500.000
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
                            Siti
                        </td>

                        <td>
                            Pengadaan ATK
                        </td>

                        <td>
                            Rp 1.750.000
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
                            Andi
                        </td>

                        <td>
                            Perjalanan Dinas
                        </td>

                        <td>
                            Rp 4.200.000
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


            <div class="activity">

                <div class="activity-icon blue">
                    ✓
                </div>

                <div>

                    <strong>
                        SPJ-2026-002 disetujui
                    </strong>

                    <span>
                        PPK menyetujui SPJ
                    </span>

                    <small>
                        10 menit yang lalu
                    </small>

                </div>

            </div>


            <div class="activity">

                <div class="activity-icon orange">
                    !
                </div>

                <div>

                    <strong>
                        SPJ-2026-003 dikembalikan
                    </strong>

                    <span>
                        Dokumen belum lengkap
                    </span>

                    <small>
                        35 menit yang lalu
                    </small>

                </div>

            </div>


            <div class="activity">

                <div class="activity-icon green">
                    +
                </div>

                <div>

                    <strong>
                        SPJ baru diajukan
                    </strong>

                    <span>
                        SPJ-2026-005 oleh Ahmad
                    </span>

                    <small>
                        1 jam yang lalu
                    </small>

                </div>

            </div>


            <div class="activity">

                <div class="activity-icon purple">
                    $
                </div>

                <div>

                    <strong>
                        Pembayaran selesai
                    </strong>

                    <span>
                        SPJ-2026-004
                    </span>

                    <small>
                        2 jam yang lalu
                    </small>

                </div>

            </div>


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

        <a href="#">
            Detail →
        </a>

    </div>


    <div class="workflow-stats">


        <div class="workflow-box">

            <div class="workflow-number">
                12
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


        <div class="workflow-box">

            <div class="workflow-number">
                8
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


        <div class="workflow-box">

            <div class="workflow-number">
                5
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


        <div class="workflow-box">

            <div class="workflow-number">
                3
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


        <div class="workflow-box completed-box">

            <div class="workflow-number">
                96
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