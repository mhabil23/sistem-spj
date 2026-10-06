<?php

namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpjController extends Controller
{
    public function index()
    {
        $userId = Auth::id() ?? 1;
        $spjs = Spj::where('user_id', $userId)->latest()->get();
        return view('teknis.spj.index', compact('spjs'));
    }

    public function create()
    {
        return view('teknis.spj.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,diajukan'
        ]);

        $dokumenKelengkapan = [];
        if ($request->has('nama_dokumen') && is_array($request->input('nama_dokumen'))) {
            foreach ($request->input('nama_dokumen') as $index => $namaDokumen) {
                $isChecked = $request->has("berkas.{$index}");
                $dokumenKelengkapan[] = [
                    'nama' => $namaDokumen,
                    'disiapkan' => $isChecked
                ];
            }
        }

        Spj::create([
            'user_id' => Auth::id() ?? 1,
            'nomor_spj' => 'SPJ-' . date('YmdHis') . '-' . rand(100, 999),
            'kegiatan' => $request->kegiatan,
            'tanggal' => $request->tanggal,
            'nilai' => 0,
            'keterangan' => null,
            'status' => $request->status,
            'dokumen_file' => count($dokumenKelengkapan) > 0 ? json_encode($dokumenKelengkapan) : null,
            'diajukan_at' => $request->status === 'diajukan' ? now() : null,
        ]);

        $msg = $request->status === 'diajukan' ? 'SPJ berhasil diajukan ke Umum.' : 'SPJ berhasil dibuat dan disimpan sebagai Draft.';

        return redirect()->route('teknis.spj.index')->with('success', $msg);
    }

    public function show(Spj $spj)
    {
        $userId = Auth::id() ?? 1;
        if ($spj->user_id !== $userId) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return view('teknis.spj.show', compact('spj'));
    }

    public function edit(Spj $spj)
    {
        $userId = Auth::id() ?? 1;
        if ($spj->user_id !== $userId) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if (!in_array($spj->status, ['draft', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara'])) {
            return redirect()->route('teknis.spj.index')->with('error', 'SPJ tidak dapat diubah karena sudah dalam proses.');
        }

        return view('teknis.spj.edit', compact('spj'));
    }

    public function update(Request $request, Spj $spj)
    {
        $userId = Auth::id() ?? 1;
        if ($spj->user_id !== $userId) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if (!in_array($spj->status, ['draft', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara'])) {
            return redirect()->route('teknis.spj.index')->with('error', 'SPJ tidak dapat diubah karena sudah dalam proses.');
        }

        $request->validate([
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,diajukan'
        ]);

        $dokumenKelengkapan = $spj->dokumen_file ? json_decode($spj->dokumen_file, true) : [];
        if ($request->has('nama_dokumen') && is_array($request->input('nama_dokumen'))) {
            $dokumenKelengkapan = [];
            foreach ($request->input('nama_dokumen') as $index => $namaDokumen) {
                $isChecked = $request->has("berkas.{$index}");
                $dokumenKelengkapan[] = [
                    'nama' => $namaDokumen,
                    'disiapkan' => $isChecked
                ];
            }
        }

        $spj->update([
            'kegiatan' => $request->kegiatan,
            'tanggal' => $request->tanggal,
            'status' => $request->status,
            'dokumen_file' => count($dokumenKelengkapan) > 0 ? json_encode($dokumenKelengkapan) : null,
            'diajukan_at' => $request->status === 'diajukan' && $spj->status !== 'diajukan' ? now() : $spj->diajukan_at,
        ]);

        $msg = $request->status === 'diajukan' ? 'SPJ berhasil diajukan.' : 'Draft SPJ berhasil diperbarui.';

        return redirect()->route('teknis.spj.index')->with('success', $msg);
    }
}
