<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoTutorial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VideoTutorialController extends Controller
{
    /**
     * Tampilkan halaman pengelolaan satu Video Tutorial Panduan utama.
     */
    public function index(): View
    {
        $video = VideoTutorial::first();

        return view('admin.video-tutorial.index', [
            'title' => 'Kelola Video Tutorial Panduan',
            'video' => $video,
        ]);
    }

    /**
     * Simpan atau perbarui video tutorial utama.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'tipe_sumber' => ['required', 'string', 'in:upload,link'],
            'url_video' => ['required_if:tipe_sumber,link', 'nullable', 'string', 'max:500'],
            'file_video' => ['nullable', 'file', 'mimes:mp4,webm,ogg,mov,mkv', 'max:204800'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'durasi' => ['nullable', 'string', 'max:20'],
        ], [
            'judul.required' => 'Judul video tutorial wajib diisi.',
            'url_video.required_if' => 'Tautan URL video wajib diisi jika memilih sumber Link Video.',
            'file_video.mimes' => 'Format berkas video harus berupa MP4, WebM, OGG, atau MOV.',
            'file_video.max' => 'Ukuran file video maksimal 200 MB.',
        ]);

        $video = VideoTutorial::first() ?: new VideoTutorial;

        if ($validated['tipe_sumber'] === 'upload') {
            if ($request->hasFile('file_video')) {
                // Hapus file lama jika ada
                if ($video->file_video && Storage::disk('public')->exists($video->file_video)) {
                    Storage::disk('public')->delete($video->file_video);
                }
                $video->file_video = $request->file('file_video')->store('videos/tutorials', 'public');
                $video->url_video = null;
                $video->youtube_id = null;
            } elseif (! $video->file_video) {
                return back()->withInput()->withErrors([
                    'file_video' => 'Silakan pilih dan unggah berkas video (MP4/WebM).',
                ]);
            }
        } else {
            // Tipe sumber link
            if ($video->file_video && Storage::disk('public')->exists($video->file_video)) {
                Storage::disk('public')->delete($video->file_video);
                $video->file_video = null;
            }
            $video->url_video = $validated['url_video'];
            $video->youtube_id = VideoTutorial::extractYouTubeId($validated['url_video']);
        }

        $video->judul = $validated['judul'];
        $video->kategori = 'semua';
        $video->tipe_sumber = $validated['tipe_sumber'];
        $video->deskripsi = $validated['deskripsi'] ?? null;
        $video->durasi = $validated['durasi'] ?? null;
        $video->urutan = 1;
        $video->is_active = $request->has('is_active');
        $video->save();

        return redirect()->route('admin.video-tutorial.index')
            ->with('success', 'Video tutorial panduan berhasil disimpan dan diperbarui.');
    }

    /**
     * Hapus video tutorial yang ada.
     */
    public function destroy(): RedirectResponse
    {
        $video = VideoTutorial::first();

        if ($video) {
            if ($video->file_video && Storage::disk('public')->exists($video->file_video)) {
                Storage::disk('public')->delete($video->file_video);
            }
            $video->delete();
        }

        return redirect()->route('admin.video-tutorial.index')
            ->with('success', 'Video tutorial berhasil dihapus.');
    }
}
