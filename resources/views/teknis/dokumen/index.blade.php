@extends('layouts.teknis')

@php
$title = 'Dokumen SPJ';
$subtitle = 'Kelola file dokumen pendukung (PDF/Gambar) untuk SPJ Anda.';
@endphp

@section('content')

<div class="page-heading">
    <div>
        <h2>Dokumen SPJ</h2>
        <p>Unggah bukti kuitansi atau dokumen pendukung untuk SPJ Anda.</p>
    </div>
</div>

@if (session('success'))
    <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif
@if ($errors->any())
    <div style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="panel">
    <div class="panel-header">
        <div>
            <h2>Data SPJ & Dokumen</h2>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nomor SPJ</th>
                    <th>Kegiatan</th>
                    <th>Status</th>
                    <th>Dokumen Terlampir</th>
                    <th>Aksi Upload</th>
                </tr>
            </thead>
            <tbody>
                @forelse($spjs as $spj)
                <tr>
                    <td><strong>{{ $spj->nomor_spj }}</strong></td>
                    <td>{{ $spj->kegiatan }}</td>
                    <td>
                        @php
                            $badgeClass = 'pending';
                            if($spj->status == 'selesai') $badgeClass = 'completed';
                            if(str_contains($spj->status, 'revisi')) $badgeClass = 'returned';
                            if($spj->status == 'draft') $badgeClass = 'draft';
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ str_replace('_', ' ', ucfirst($spj->status)) }}</span>
                    </td>
                    <td>
                        @if($spj->dokumen_file)
                            <a href="{{ Storage::url($spj->dokumen_file) }}" target="_blank" style="color: #10b981; text-decoration: none; font-weight: 500;">Lihat Dokumen</a>
                        @else
                            <span style="color: #6b7280; font-style: italic;">Belum ada dokumen</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('teknis.dokumen.upload', $spj->id) }}" method="POST" enctype="multipart/form-data" style="display: flex; gap: 0.5rem; align-items: center;">
                            @csrf
                            <input type="file" name="dokumen_file" required accept=".pdf,.jpg,.jpeg,.png" style="font-size: 0.875rem;">
                            <button type="submit" class="btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; border: none; cursor: pointer; border-radius: 0.25rem;">Upload</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:30px;">Belum ada SPJ.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
