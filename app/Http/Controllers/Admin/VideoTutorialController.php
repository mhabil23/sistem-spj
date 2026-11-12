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
     * Tampilkan daftar video tutorial di panel Admin.
     */
    public function index(Request $request): View
    {
        $query = VideoTutorial::query();

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === '1');
        }

        if ($request->filled('search')) {
            $search = '%'.$request->search.'%';
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', $search)
                    ->orWhere('deskripsi', 'like', $search);
            });
        }

        $tutorials = $query->orderBy('urutan', 'asc')->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $stats = [
            'total' => VideoTutorial::count(),
            'aktif' => VideoTutorial::where('is_active', true)->count(),
            'nonaktif' => VideoTutorial::where('is_active', false)->count(),
            'link' => VideoTutorial::where('tipe_sumber', 'link')->count(),
            'upload' => VideoTutorial::where('tipe_sumber', 'upload')->count(),
        ];

        return view('admin.video-tutorial.index', [
            'title' => 'Kelola Video Tutorial Panduan',
            'tutorials' => $tutorials,
            'stats' => $stats,
        ]);
    }

    /**
     * Simpan video tutorial baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'in:semua,teknis,umum,ppk,ppspm,bendahara'],
            'tipe_sumber' => ['required', 'string', 'in:link,upload'],
            'url_video' => ['required_if:tipe_sumber,link', 'nullable', 'string', 'max:500'],
            'file_video' => ['required_if:tipe_sumber,upload', 'nullable', 'file', 'mimes:mp4,webm,ogg,mov,mkv', 'max:102400'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'durasi' => ['nullable', 'string', 'max:20'],
            'urutan' => ['nullable', 'integer', 'min:1'],
        ], [
            'judul.required' => 'Judul video tutorial wajib diisi.',
            'kategori.required' => 'Kategori sasaran wajib dipilih.',
            'url_video.required_if' => 'Link URL video wajib diisi jika sumber dipilih Link Video.',
            'file_video.required_if' => 'File video wajib diunggah jika sumber dipilih Upload File.',
            'file_video.mimes' => 'Format file video harus berupa MP4, WebM, OGG, atau MOV.',
            'file_video.max' => 'Ukuran file video maksimal 100 MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_video') && $validated['tipe_sumber'] === 'upload') {
            $filePath = $request->file('file_video')->store('videos/tutorials', 'public');
        }

        $youtubeId = null;
        if ($validated['tipe_sumber'] === 'link' && ! empty($validated['url_video'])) {
            $youtubeId = VideoTutorial::extractYouTubeId($validated['url_video']);
        }

        VideoTutorial::create([
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'],
            'tipe_sumber' => $validated['tipe_sumber'],
            'url_video' => $validated['tipe_sumber'] === 'link' ? $validated['url_video'] : null,
            'file_video' => $filePath,
            'youtube_id' => $youtubeId,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'durasi' => $validated['durasi'] ?? null,
            'urutan' => $validated['urutan'] ?? (VideoTutorial::max('urutan') + 1),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.video-tutorial.index')
            ->with('success', 'Video tutorial baru berhasil ditambahkan.');
    }

    /**
     * Perbarui video tutorial yang sudah ada.
     */
    public function update(Request $request, VideoTutorial $videoTutorial): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'in:semua,teknis,umum,ppk,ppspm,bendahara'],
            'tipe_sumber' => ['required', 'string', 'in:link,upload'],
            'url_video' => ['required_if:tipe_sumber,link', 'nullable', 'string', 'max:500'],
            'file_video' => ['nullable', 'file', 'mimes:mp4,webm,ogg,mov,mkv', 'max:102400'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'durasi' => ['nullable', 'string', 'max:20'],
            'urutan' => ['nullable', 'integer', 'min:1'],
        ], [
            'judul.required' => 'Judul video tutorial wajib diisi.',
            'kategori.required' => 'Kategori sasaran wajib dipilih.',
            'url_video.required_if' => 'Link URL video wajib diisi jika sumber dipilih Link Video.',
            'file_video.mimes' => 'Format file video harus berupa MP4, WebM, OGG, atau MOV.',
            'file_video.max' => 'Ukuran file video maksimal 100 MB.',
        ]);

        $filePath = $videoTutorial->file_video;
        if ($request->hasFile('file_video') && $validated['tipe_sumber'] === 'upload') {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file_video')->store('videos/tutorials', 'public');
        }

        $youtubeId = $videoTutorial->youtube_id;
        if ($validated['tipe_sumber'] === 'link') {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
                $filePath = null;
            }
            $youtubeId = VideoTutorial::extractYouTubeId($validated['url_video']);
        } else {
            $youtubeId = null;
        }

        $videoTutorial->update([
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'],
            'tipe_sumber' => $validated['tipe_sumber'],
            'url_video' => $validated['tipe_sumber'] === 'link' ? $validated['url_video'] : null,
            'file_video' => $filePath,
            'youtube_id' => $youtubeId,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'durasi' => $validated['durasi'] ?? null,
            'urutan' => $validated['urutan'] ?? $videoTutorial->urutan,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.video-tutorial.index')
            ->with('success', 'Video tutorial berhasil diperbarui.');
    }

    /**
     * Hapus video tutorial.
     */
    public function destroy(VideoTutorial $videoTutorial): RedirectResponse
    {
        if ($videoTutorial->file_video && Storage::disk('public')->exists($videoTutorial->file_video)) {
            Storage::disk('public')->delete($videoTutorial->file_video);
        }

        $videoTutorial->delete();

        return redirect()->route('admin.video-tutorial.index')
            ->with('success', 'Video tutorial berhasil dihapus.');
    }

    /**
     * Beralih status aktif / nonaktif video tutorial.
     */
    public function toggle(VideoTutorial $videoTutorial): RedirectResponse
    {
        $videoTutorial->update([
            'is_active' => ! $videoTutorial->is_active,
        ]);

        $statusText = $videoTutorial->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.video-tutorial.index')
            ->with('success', "Status video tutorial berhasil {$statusText}.");
    }
}
