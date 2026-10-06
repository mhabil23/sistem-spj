<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keputusan SPJ - PPK</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #e5e7eb; }
        .text-center { text-align: center; }
        h2 { text-align: center; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #4b5563; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>Laporan Keputusan SPJ</h2>
    <div class="subtitle">Modul Pejabat Pembuat Komitmen (PPK)</div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Update</th>
                <th>Nomor SPJ</th>
                <th>Pengaju</th>
                <th>Kegiatan</th>
                <th>Nilai (Rp)</th>
                <th>Status Keputusan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($spjs as $index => $spj)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $spj->updated_at->format('d M Y H:i') }}</td>
                <td>{{ $spj->nomor_spj }}</td>
                <td>{{ $spj->user->name ?? 'Teknis' }}</td>
                <td>{{ $spj->kegiatan }}</td>
                <td>{{ number_format($spj->nilai, 0, ',', '.') }}</td>
                <td>
                    @if($spj->status == 'revisi_ppk')
                        Dikembalikan
                    @else
                        Disetujui
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
