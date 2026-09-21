@extends('layouts.teknis')

@php
$title = 'Riwayat SPJ';
$subtitle = 'Lihat riwayat pengajuan SPJ Anda yang telah selesai atau dikembalikan.';
@endphp

@section('content')

<div class="page-heading">
    <div>
        <h2>Riwayat Pengajuan</h2>
        <p>Semua dokumen SPJ Anda yang berstatus Selesai atau Dikembalikan.</p>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <div>
            <h2>Data Riwayat</h2>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nomor SPJ</th>
                    <th>Uraian Kegiatan</th>
                    <th>Tanggal Diperbarui</th>
                    <th>Nilai</th>
                    <th>Status Akhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatSpjs as $spj)
                <tr>
                    <td><strong>{{ $spj->nomor_spj }}</strong></td>
                    <td>{{ $spj->kegiatan }}</td>
                    <td>{{ $spj->updated_at->format('d M Y, H:i') }}</td>
                    <td>Rp {{ number_format($spj->nilai, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $badgeClass = $spj->status == 'selesai' ? 'completed' : 'returned';
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ str_replace('_', ' ', ucfirst($spj->status)) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('teknis.spj.show', $spj->id) }}" style="color: #3b82f6; text-decoration: none;">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:30px;">Belum ada riwayat SPJ.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
