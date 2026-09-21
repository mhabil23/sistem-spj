<?php

namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    public function index()
    {
        $spjs = Spj::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('teknis.dokumen.index', compact('spjs'));
    }

    public function upload(Request $request, Spj $spj)
    {
        if ($spj->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'dokumen_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('dokumen_file')) {
            // Hapus dokumen lama jika ada
            if ($spj->dokumen_file) {
                Storage::disk('public')->delete($spj->dokumen_file);
            }

            $path = $request->file('dokumen_file')->store('dokumen_spjs', 'public');
            $spj->update(['dokumen_file' => $path]);
        }

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }
}
