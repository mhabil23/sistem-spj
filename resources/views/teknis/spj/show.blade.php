@extends('layouts.teknis')

@php
$title = 'Detail SPJ';
$subtitle = 'Informasi lengkap pengajuan SPJ Anda.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/spj.css'])
@endpush


<div class="page-heading">
    <div>
        <h2>{{ $spj->nomor_spj }}</h2>
        <p>Diajukan pada: {{ $spj->created_at->format('d F Y H:i') }}</p>
    </div>
    <a href="{{ route('teknis.spj.index') }}" class="btn-primary" style="background-color: #6b7280;">
        Kembali
    </a>
</div>

<div class="panel">
    <div class="panel-header">
        <div>
            <h2>Data Utama SPJ</h2>
        </div>
        <div>
            @php
                $badgeClass = 'pending';
                if($spj->status == 'selesai') $badgeClass = 'completed';
                if($spj->status == 'dikembalikan') $badgeClass = 'returned';
                if($spj->status == 'draft') $badgeClass = 'draft';
            @endphp
            <span class="badge {{ $badgeClass }}" style="font-size: 1rem; padding: 0.5rem 1rem;">
                Status: {{ ucfirst($spj->status) }}
            </span>
        </div>
    </div>
    <div style="padding: 1.5rem;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <tr style="border-bottom: 1px solid #f3f4f6;">
                <th style="padding: 1rem 0; width: 200px; color: #4b5563;">Kegiatan</th>
                <td style="padding: 1rem 0; font-weight: 500;">{{ $spj->kegiatan }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f3f4f6;">
                <th style="padding: 1rem 0; color: #4b5563;">Tanggal Pelaksanaan</th>
                <td style="padding: 1rem 0;">{{ $spj->tanggal->format('d F Y') }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f3f4f6;">
                <th style="padding: 1rem 0; color: #4b5563;">Nilai (Rp)</th>
                <td style="padding: 1rem 0; font-weight: 500; color: #15803d;">Rp {{ number_format($spj->nilai, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th style="padding: 1rem 0; color: #4b5563;">Keterangan</th>
                <td style="padding: 1rem 0;">{{ $spj->keterangan ?: '-' }}</td>
            </tr>
        </table>
        
        @if($spj->status === 'draft' || $spj->status === 'dikembalikan')
        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <a href="{{ route('teknis.spj.edit', $spj->id) }}" class="btn-primary" style="background-color: #f59e0b; border: none; text-decoration: none;">
                Edit SPJ Ini
            </a>
        </div>
        @endif
    </div>
</div>

@endsection

