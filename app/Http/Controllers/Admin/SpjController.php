<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;

class SpjController extends Controller
{
    public function index()
    {
        $spjs = Spj::latest()->get();

        return view('admin.spj', compact('spjs'));
    }

    public function create()
    {
        return view('admin.spj-create');
    }

    public function show(Spj $spj)
    {
        return view('admin.spj-show', compact('spj'));
    }

    public function edit(Spj $spj)
    {
        return view('admin.spj-edit', compact('spj'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_spj' => 'required|string|max:100|unique:spjs,nomor_spj',
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:draft,diajukan,diproses,selesai,dikembalikan',
        ]);

        Spj::create([
            'nomor_spj' => $request->nomor_spj,
            'kegiatan' => $request->kegiatan,
            'tanggal' => $request->tanggal,
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.spj.index')
            ->with('success', 'SPJ berhasil ditambahkan.');
    }

    public function update(Request $request, Spj $spj)
    {
        $validated = $request->validate([
            'nomor_spj' => 'required|string|max:255|unique:spjs,nomor_spj,' . $spj->id,
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:draft,diajukan,diproses,selesai,dikembalikan',
        ]);

        $spj->update($validated);

        return redirect()
            ->route('spj.index')
            ->with('success', 'Data SPJ berhasil diperbarui.');
    }

    public function destroy(Spj $spj)
    {
        $spj->delete();

        return redirect()
            ->route('spj.index')
            ->with('success', 'Data SPJ berhasil dihapus.');
    }
}
