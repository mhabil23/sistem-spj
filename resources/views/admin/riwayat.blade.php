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

                @forelse($riwayat as $history)

                <tr>

                    {{-- DOKUMEN --}}
                    <td>
                        <div class="document-info">

                            <div class="document-icon">
                                SPJ
                            </div>

                            <div>
                                <strong>
                                    {{ $history->spj?->kegiatan ?? 'SPJ' }}
                                </strong>

                                <span>
                                    {{ $history->spj?->nomor_spj ?? '-' }}
                                </span>
                            </div>

                        </div>
                    </td>


                    {{-- AKTIVITAS --}}
                    <td>
                        <div class="activity">

                            <strong>
                                @if($history->spj?->status === 'diajukan')
                                Pengajuan SPJ
                                @elseif($history->spj?->status === 'diproses')
                                Pemeriksaan SPJ
                                @elseif($history->spj?->status === 'selesai')
                                SPJ Selesai
                                @elseif($history->spj?->status === 'dikembalikan')
                                Revisi SPJ
                                @else
                                Proses SPJ
                                @endif
                            </strong>

                            <span>
                                @if($history->spj?->status === 'diajukan')
                                Dokumen diajukan
                                @elseif($history->spj?->status === 'diproses')
                                Dokumen sedang diperiksa
                                @elseif($history->spj?->status === 'selesai')
                                Proses SPJ telah selesai
                                @elseif($history->spj?->status === 'dikembalikan')
                                Dokumen perlu diperbaiki
                                @else
                                Aktivitas SPJ
                                @endif
                            </span>

                        </div>
                    </td>


                    {{-- PENGGUNA --}}
                    <td>
                        <div class="user-info">

                            <div class="user-avatar">
                                {{ strtoupper(substr($history->user?->name ?? 'U', 0, 2)) }}
                            </div>

                            <span>
                                {{ $history->user?->name ?? 'Sistem' }}
                            </span>

                        </div>
                    </td>


                    {{-- STATUS --}}
                    <td>

                        @if($history->spj?->status === 'diajukan')

                        <span class="history-status processing">
                            <i></i>
                            Diajukan
                        </span>

                        @elseif($history->spj?->status === 'diproses')

                        <span class="history-status processing">
                            <i></i>
                            Diproses
                        </span>

                        @elseif($history->spj?->status === 'selesai')

                        <span class="history-status completed">
                            <i></i>
                            Selesai
                        </span>

                        @elseif($history->spj?->status === 'dikembalikan')

                        <span class="history-status returned">
                            <i></i>
                            Dikembalikan
                        </span>

                        @else

                        <span class="history-status">
                            <i></i>
                            {{ ucfirst($history->spj?->status ?? 'Tidak diketahui') }}
                        </span>

                        @endif

                    </td>


                    {{-- WAKTU --}}
                    <td>

                        <div class="time-info">

                            <strong>
                                {{ $history->created_at?->format('d M Y') ?? '-' }}
                            </strong>

                            <span>
                                {{ $history->created_at?->format('H:i') ?? '-' }}
                                WIB
                            </span>

                        </div>

                    </td>


                    {{-- AKSI --}}
                    <td>

                        <a
                            href="{{ route('admin.spj.edit', $history->spj_id) }}"
                            class="history-action"
                            title="Lihat SPJ">
                        </a>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px;">
                        Belum ada riwayat proses SPJ.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>


        {{-- FOOTER / PAGINATION --}}
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