<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checklist Verifikasi SPJ</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; line-height: 1.5; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; margin-bottom: 20px; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 12px; }
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 5px; vertical-align: top; }
        .info-table td:first-child { width: 150px; font-weight: bold; }
        .checklist-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .checklist-table th, .checklist-table td { border: 1px solid #333; padding: 10px; text-align: left; }
        .checklist-table th { background-color: #f0f0f0; }
        .box { width: 20px; height: 20px; border: 1px solid #333; display: inline-block; }
        .signature-section { width: 100%; margin-top: 50px; }
        .signature-box { float: right; width: 300px; text-align: center; }
        .signature-line { margin-top: 80px; border-bottom: 1px solid #333; }
        .clear { clear: both; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LEMBAR CHECKLIST VERIFIKASI SPJ</h1>
        <p>BAGIAN PPK / PPSPM</p>
    </div>

    <table class="info-table">
        <tr>
            <td>Nomor SPJ</td>
            <td>: {{ $spj->nomor_spj }}</td>
        </tr>
        <tr>
            <td>Tanggal Pengajuan</td>
            <td>: {{ \Carbon\Carbon::parse($spj->diajukan_at ?? $spj->created_at)->format('d F Y, H:i') }}</td>
        </tr>
        <tr>
            <td>Staf Pengaju</td>
            <td>: {{ $spj->user->name ?? 'Staf Teknis' }}</td>
        </tr>
        <tr>
            <td>Nama Kegiatan</td>
            <td>: {{ $spj->kegiatan }}</td>
        </tr>
        <tr>
            <td>Total Nilai</td>
            <td>: Rp {{ number_format($spj->nilai, 0, ',', '.') }}</td>
        </tr>
    </table>

    <p style="font-weight: bold;">DAFTAR PEMERIKSAAN DOKUMEN:</p>
    
    <table class="checklist-table">
        <thead>
            <tr>
                <th style="width: 50px; text-align: center;">No.</th>
                <th>Item Pemeriksaan</th>
                <th style="width: 80px; text-align: center;">Ada / Sesuai</th>
                <th style="width: 80px; text-align: center;">Kekurangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td>Kuitansi asli bermaterai (jika nilai > Rp5.000.000)</td>
                <td style="text-align: center;"><div class="box"></div></td>
                <td style="text-align: center;"><div class="box"></div></td>
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td>Tanda tangan lengkap pada nota / invoice</td>
                <td style="text-align: center;"><div class="box"></div></td>
                <td style="text-align: center;"><div class="box"></div></td>
            </tr>
            <tr>
                <td style="text-align: center;">3</td>
                <td>Kesesuaian nominal kuitansi dengan rekapitulasi</td>
                <td style="text-align: center;"><div class="box"></div></td>
                <td style="text-align: center;"><div class="box"></div></td>
            </tr>
            <tr>
                <td style="text-align: center;">4</td>
                <td>Dokumen pendukung (Daftar hadir, foto kegiatan, dll)</td>
                <td style="text-align: center;"><div class="box"></div></td>
                <td style="text-align: center;"><div class="box"></div></td>
            </tr>
            <tr>
                <td style="text-align: center;">5</td>
                <td>Perhitungan Pajak (PPN/PPh) sudah benar</td>
                <td style="text-align: center;"><div class="box"></div></td>
                <td style="text-align: center;"><div class="box"></div></td>
            </tr>
        </tbody>
    </table>

    <div style="border: 1px solid #333; padding: 10px; min-height: 80px; margin-bottom: 20px;">
        <strong>Catatan Pemeriksa:</strong><br><br>
        {{ $spj->catatan_internal ?? '.......................................................................................................................................................................' }}
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p>Telah diperiksa pada tanggal: <strong>{{ \Carbon\Carbon::now()->format('d M Y') }}</strong></p>
            <p>Petugas Verifikasi PPK,</p>
            <div class="signature-line"></div>
            <p style="margin-top: 5px;">( {{ auth()->user()->name ?? '.....................................' }} )</p>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
