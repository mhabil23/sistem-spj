@extends('layouts.admin')

@php
$title = 'Pengguna';
$subtitle = 'Kelola akun dan hak akses pengguna sistem SPJ.';
@endphp

@section('content')

<div class="user-page">

    {{-- HEADER --}}
    <div class="page-header">

        <div>
            <h2>Pengguna</h2>
            <p>
                Kelola akun pengguna dan role dalam sistem SPJ.
            </p>
        </div>

        <button type="button" class="btn-primary">
            <span>＋</span>
            Tambah Pengguna
        </button>

    </div>


    {{-- STATISTIK --}}
    <div class="user-stats">

        <div class="user-stat-card">

            <div class="user-stat-icon blue">
                👥
            </div>

            <div>
                <span>Total Pengguna</span>
                <strong>24</strong>
                <small>Seluruh akun</small>
            </div>

        </div>


        <div class="user-stat-card">

            <div class="user-stat-icon green">
                ✓
            </div>

            <div>
                <span>Pengguna Aktif</span>
                <strong>22</strong>
                <small>Akun aktif</small>
            </div>

        </div>


        <div class="user-stat-card">

            <div class="user-stat-icon orange">
                ◷
            </div>

            <div>
                <span>Menunggu Aktivasi</span>
                <strong>2</strong>
                <small>Perlu diperiksa</small>
            </div>

        </div>


        <div class="user-stat-card">

            <div class="user-stat-icon purple">
                ◈
            </div>

            <div>
                <span>Role</span>
                <strong>5</strong>
                <small>Jenis pengguna</small>
            </div>

        </div>

    </div>


    {{-- DATA PENGGUNA --}}
    <div class="user-panel">

        {{-- TOOLBAR --}}
        <div class="user-toolbar">

            <div class="toolbar-title">

                <h3>Daftar Pengguna</h3>

                <p>
                    Daftar seluruh pengguna yang terdaftar dalam sistem.
                </p>

            </div>


            <div class="toolbar-actions">

                <div class="search-box">

                    <span>⌕</span>

                    <input
                        type="text"
                        placeholder="Cari pengguna...">

                </div>


                <select class="filter-select">

                    <option value="">
                        Semua Role
                    </option>

                    <option value="admin">
                        Admin
                    </option>

                    <option value="teknis">
                        Teknis
                    </option>

                    <option value="umum">
                        Umum / PPSPM
                    </option>

                    <option value="ppk">
                        PPK
                    </option>

                    <option value="bendahara">
                        Bendahara
                    </option>

                </select>


                <select class="filter-select">

                    <option value="">
                        Semua Status
                    </option>

                    <option value="aktif">
                        Aktif
                    </option>

                    <option value="nonaktif">
                        Nonaktif
                    </option>

                </select>

            </div>

        </div>


        {{-- TABLE --}}
        <div class="user-table-wrapper">

            <table class="user-table">

                <thead>

                    <tr>

                        <th>Pengguna</th>

                        <th>Email</th>

                        <th>Role</th>

                        <th>Status</th>

                        <th>Terakhir Login</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>

                    {{-- ADMIN --}}
                    <tr>

                        <td>

                            <div class="user-profile">

                                <div class="table-avatar admin">
                                    A
                                </div>

                                <div>

                                    <strong>
                                        Administrator
                                    </strong>

                                    <span>
                                        ID: USR-001
                                    </span>

                                </div>

                            </div>

                        </td>

                        <td>
                            admin@bps.go.id
                        </td>

                        <td>

                            <span class="role-badge admin">
                                Admin
                            </span>

                        </td>

                        <td>

                            <span class="status-badge active">
                                <i></i>
                                Aktif
                            </span>

                        </td>

                        <td>
                            Hari ini, 07:21
                        </td>

                        <td>

                            <div class="action-buttons">

                                <button
                                    class="action-btn view"
                                    title="Lihat">
                                    👁
                                </button>

                                <button
                                    class="action-btn edit"
                                    title="Edit">
                                    ✎
                                </button>

                                <button
                                    class="action-btn delete"
                                    title="Hapus">
                                    🗑
                                </button>

                            </div>

                        </td>

                    </tr>


                    {{-- TEKNIS --}}
                    <tr>

                        <td>

                            <div class="user-profile">

                                <div class="table-avatar teknis">
                                    T
                                </div>

                                <div>

                                    <strong>
                                        Ahmad Fauzan
                                    </strong>

                                    <span>
                                        ID: USR-002
                                    </span>

                                </div>

                            </div>

                        </td>

                        <td>
                            ahmad@bps.go.id
                        </td>

                        <td>

                            <span class="role-badge teknis">
                                Teknis
                            </span>

                        </td>

                        <td>

                            <span class="status-badge active">
                                <i></i>
                                Aktif
                            </span>

                        </td>

                        <td>
                            Hari ini, 07:05
                        </td>

                        <td>

                            <div class="action-buttons">

                                <button class="action-btn view">
                                    👁
                                </button>

                                <button class="action-btn edit">
                                    ✎
                                </button>

                                <button class="action-btn delete">
                                    🗑
                                </button>

                            </div>

                        </td>

                    </tr>


                    {{-- UMUM --}}
                    <tr>

                        <td>

                            <div class="user-profile">

                                <div class="table-avatar umum">
                                    S
                                </div>

                                <div>

                                    <strong>
                                        Siti Rahma
                                    </strong>

                                    <span>
                                        ID: USR-003
                                    </span>

                                </div>

                            </div>

                        </td>

                        <td>
                            siti@bps.go.id
                        </td>

                        <td>

                            <span class="role-badge umum">
                                Umum / PPSPM
                            </span>

                        </td>

                        <td>

                            <span class="status-badge active">
                                <i></i>
                                Aktif
                            </span>

                        </td>

                        <td>
                            Kemarin, 16:43
                        </td>

                        <td>

                            <div class="action-buttons">

                                <button class="action-btn view">
                                    👁
                                </button>

                                <button class="action-btn edit">
                                    ✎
                                </button>

                                <button class="action-btn delete">
                                    🗑
                                </button>

                            </div>

                        </td>

                    </tr>


                    {{-- PPK --}}
                    <tr>

                        <td>

                            <div class="user-profile">

                                <div class="table-avatar ppk">
                                    B
                                </div>

                                <div>

                                    <strong>
                                        Budi Santoso
                                    </strong>

                                    <span>
                                        ID: USR-004
                                    </span>

                                </div>

                            </div>

                        </td>

                        <td>
                            budi@bps.go.id
                        </td>

                        <td>

                            <span class="role-badge ppk">
                                PPK
                            </span>

                        </td>

                        <td>

                            <span class="status-badge active">
                                <i></i>
                                Aktif
                            </span>

                        </td>

                        <td>
                            20 Sep 2026, 15:20
                        </td>

                        <td>

                            <div class="action-buttons">

                                <button class="action-btn view">
                                    👁
                                </button>

                                <button class="action-btn edit">
                                    ✎
                                </button>

                                <button class="action-btn delete">
                                    🗑
                                </button>

                            </div>

                        </td>

                    </tr>


                    {{-- BENDAHARA --}}
                    <tr>

                        <td>

                            <div class="user-profile">

                                <div class="table-avatar bendahara">
                                    D
                                </div>

                                <div>

                                    <strong>
                                        Dewi Lestari
                                    </strong>

                                    <span>
                                        ID: USR-005
                                    </span>

                                </div>

                            </div>

                        </td>

                        <td>
                            dewi@bps.go.id
                        </td>

                        <td>

                            <span class="role-badge bendahara">
                                Bendahara
                            </span>

                        </td>

                        <td>

                            <span class="status-badge inactive">
                                <i></i>
                                Nonaktif
                            </span>

                        </td>

                        <td>
                            18 Sep 2026, 09:12
                        </td>

                        <td>

                            <div class="action-buttons">

                                <button class="action-btn view">
                                    👁
                                </button>

                                <button class="action-btn edit">
                                    ✎
                                </button>

                                <button class="action-btn delete">
                                    🗑
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
                Menampilkan <strong>1–5</strong> dari
                <strong>24</strong> pengguna
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
                    4
                </button>

                <button>
                    5
                </button>

                <button>
                    ›
                </button>

            </div>

        </div>

    </div>

</div>

@endsection