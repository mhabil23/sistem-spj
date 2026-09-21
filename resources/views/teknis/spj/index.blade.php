@extends('layouts.teknis')

@php
$title = 'Daftar SPJ';
$subtitle = 'Kelola semua pengajuan SPJ Anda di sini.';
@endphp

@section('content')

<!-- PAGE HEADER -->
<div class="page-heading">
    <div>
        <h2>Daftar SPJ Anda</h2>
        <p>Lihat dan kelola dokumen SPJ yang telah Anda buat.</p>
    </div>
    <a href="{{ route('teknis.spj.create') }}" class="btn-primary">
        <span>+</span> Buat SPJ Baru
    </a>
</div>

<!-- ALERT SUCCESS/ERROR -->
@if (session('success'))
    <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
        {{ session('error') }}
    </div>
@endif

<!-- PANEL -->
<div class="panel">
    <div class="panel-header">
        <div>
            <h2>Data SPJ</h2>
            <p>Seluruh daftar pengajuan Anda.</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nomor SPJ</th>
                    <th>Uraian Kegiatan</th>
                    <th>Tanggal</th>
                    <th>Nilai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($spjs as $spj)
                <tr>
                    <td><strong>{{ $spj->nomor_spj }}</strong></td>
                    <td>{{ $spj->kegiatan }}</td>
                    <td>{{ $spj->tanggal->format('d M Y') }}</td>
                    <td>Rp {{ number_format($spj->nilai, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $badgeClass = 'pending';
                            if($spj->status == 'selesai') $badgeClass = 'completed';
                            if($spj->status == 'dikembalikan') $badgeClass = 'returned';
                            if($spj->status == 'draft') $badgeClass = 'draft';
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($spj->status) }}</span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('teknis.spj.show', $spj->id) }}" style="color: #3b82f6; text-decoration: none;">Detail</a>
                            @if($spj->status === 'draft' || $spj->status === 'dikembalikan')
                            <a href="{{ route('teknis.spj.edit', $spj->id) }}" style="color: #f59e0b; text-decoration: none;">Edit</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:30px;">Belum ada pengajuan SPJ.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
