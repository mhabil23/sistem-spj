<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pencairan SPJ - Bendahara</title>
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
    <h2>Laporan Pencairan SPJ</h2>
    <div class="subtitle">Modul Bendahara (Pencairan Dana)</div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Cair</th>
                <th>Nomor SPJ</th>
                <th>Nomor SPM</th>
                <th>Pengaju</th>
                <th>Nilai (Rp)</th>
                <th>Bukti Transfer</th>
            </tr>
        </thead>
        <tbody>
            @foreach($spjs as $index => $spj)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $spj->diselesaikan_at ? \Carbon\Carbon::parse($spj->diselesaikan_at)->format('d M Y H:i') : $spj->updated_at->format('d M Y H:i') }}</td>
                <td>{{ $spj->nomor_spj }}</td>
                <td>{{ $spj->nomor_spm ?? '-' }}</td>
                <td>{{ $spj->user->name ?? 'Teknis' }}</td>
                <td>{{ number_format($spj->nilai, 0, ',', '.') }}</td>
                <td>{{ $spj->bukti_transfer ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
