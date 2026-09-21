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
}
