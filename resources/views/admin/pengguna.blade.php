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

        <a href="{{ route('admin.pengguna.create') }}" class="btn-primary">
            <span>+</span>
            Tambah Pengguna
        </a>

    </div>


    {{-- STATISTIK --}}
    <div class="user-stats">

        <div class="user-stat-card">

            <div class="user-stat-icon blue">
                👥
            </div>

            <div>
                <span>Total Pengguna</span>
                <strong>{{ $users->count() }}</strong>
                <small>Seluruh akun</small>
            </div>

        </div>


        <div class="user-stat-card">

            <div class="user-stat-icon green">
                ✓
            </div>

            <div>
                <span>Pengguna Aktif</span>
                <strong>{{ $users->where('status', 'aktif')->count() }}</strong>
                <small>Akun aktif</small>
            </div>

        </div>


        <div class="user-stat-card">

            <div class="user-stat-icon orange">
                ◷
            </div>

            <div>
                <span>Menunggu Aktivasi</span>
                <strong>{{ $users->where('status', 'nonaktif')->count() }}</strong>
                <small>Perlu diperiksa</small>
            </div>

        </div>


        <div class="user-stat-card">

            <div class="user-stat-icon purple">
                ◈
            </div>

            <div>
                <span>Role</span>
                <strong>{{ $users->pluck('role')->unique()->count() }}</strong>
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

                    @forelse($users as $user)

                    <tr>

                        {{-- PENGGUNA --}}
                        <td>
                            <div class="user-profile">

                                <div class="table-avatar {{ $user->role }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>

                                <div>
                                    <strong>
                                        {{ $user->name }}
                                    </strong>

                                    <span>
                                        ID: USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>

                            </div>
                        </td>


                        {{-- EMAIL --}}
                        <td>
                            {{ $user->email }}
                        </td>


                        {{-- ROLE --}}
                        <td>

                            <span class="role-badge {{ $user->role }}">

                                @if($user->role === 'admin')
                                Admin

                                @elseif($user->role === 'teknis')
                                Teknis

                                @elseif($user->role === 'umum')
                                Umum / PPSPM

                                @elseif($user->role === 'ppk')
                                PPK

                                @elseif($user->role === 'bendahara')
                                Bendahara

                                @else
                                {{ ucfirst($user->role) }}
                                @endif

                            </span>

                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if($user->status === 'aktif')

                            <span class="status-badge active">
                                <i></i>
                                Aktif
                            </span>

                            @else

                            <span class="status-badge inactive">
                                <i></i>
                                Nonaktif
                            </span>

                            @endif

                        </td>


                        {{-- TERAKHIR DIPERBARUI --}}
                        <td>
                            {{ $user->updated_at
                    ? $user->updated_at->format('d M Y, H:i')
                    : '-' }}
                        </td>


                        {{-- AKSI --}}
                        <td>

                            <div class="action-buttons">

                                {{-- EDIT --}}
                                <a
                                    href="{{ route('admin.pengguna.edit', $user->id) }}"
                                    class="action-btn edit"
                                    title="Edit">

                                    <i class="bi bi-pencil"></i>

                                </a>


                                {{-- HAPUS --}}
                                <form
                                    action="{{ route('admin.pengguna.destroy', $user->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="action-btn delete"
                                        title="Hapus">

                                        <i class="bi bi-trash3"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6" style="text-align: center;">
                            Belum ada pengguna yang terdaftar.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- FOOTER --}}
        <div class="table-footer">

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