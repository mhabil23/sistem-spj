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
            'nomor_spj' => 'required|string|max:100|unique:spjs,nomor_spj',
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:draft,diajukan'
        ]);

        Spj::create([
            'user_id' => Auth::id() ?? 1,
            'nomor_spj' => $request->nomor_spj,
            'kegiatan' => $request->kegiatan,
            'tanggal' => $request->tanggal,
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
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
            'nomor_spj' => 'required|string|max:100|unique:spjs,nomor_spj,'.$spj->id,
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:draft,diajukan'
        ]);

        $spj->update([
            'nomor_spj' => $request->nomor_spj,
            'kegiatan' => $request->kegiatan,
            'tanggal' => $request->tanggal,
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'diajukan_at' => $request->status === 'diajukan' && $spj->status !== 'diajukan' ? now() : $spj->diajukan_at,
        ]);

        $msg = $request->status === 'diajukan' ? 'SPJ berhasil diajukan.' : 'Draft SPJ berhasil diperbarui.';

        return redirect()->route('teknis.spj.index')->with('success', $msg);
    }
}
