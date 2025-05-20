<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Komentar;
use Illuminate\Http\Request;

class KomentarController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display comments by status for an article.
     *
     * @param  int  $artikelId
     * @param  string  $status
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($artikelId, $status = 'all')
    {
        $artikel = Artikel::findOrFail($artikelId);
        
        if ($status == 'all') {
            $komentars = Komentar::where('id_artikel', $artikelId)->orderBy('created_at', 'desc')->get();
        } else {
            $komentars = Komentar::where('id_artikel', $artikelId)
                ->where('status', $status)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('admin.artikel.komentar.index', compact('artikel', 'komentars', 'status'));
    }

    /**
     * Update comment status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'selected_ids' => 'nullable|array',
            'selected_ids.*' => 'exists:komentar,id_komentar',
        ]);

        if ($id == 0 && $request->has('selected_ids')) {
            // Bulk update
            Komentar::whereIn('id_komentar', $request->selected_ids)
                ->update(['status' => $request->status]);
            
            $count = count($request->selected_ids);
            $message = $count . ' komentar berhasil ' . ($request->status === 'approved' ? 'disetujui' : 'ditolak') . '.';
        } else if ($id == 0) {
            // Handle "Approve All Pending" action - get all pending comments for the article
            $artikelId = $request->input('artikelId');
            
            if (!$artikelId) {
                return redirect()->back()->with('error', 'ID artikel diperlukan untuk operasi ini.');
            }
            
            $count = Komentar::where('id_artikel', $artikelId)
                ->where('status', 'pending')
                ->update(['status' => $request->status]);
            
            $message = $count . ' komentar pending berhasil ' . ($request->status === 'approved' ? 'disetujui' : 'ditolak') . '.';
        } else {
            // Single update
            $komentar = Komentar::findOrFail($id);
            $komentar->status = $request->status;
            $komentar->save();
            
            // Prepare message based on status
            switch ($request->status) {
                case 'approved':
                    $message = 'Komentar berhasil disetujui.';
                    break;
                case 'rejected':
                    $message = 'Komentar berhasil ditolak.';
                    break;
                default:
                    $message = 'Status komentar berhasil diperbarui.';
                    break;
            }
        }
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified comment from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if ($id == 0 && $request->has('selected_ids')) {
            // Bulk delete
            $count = 0;
            $artikelId = null;
            
            foreach ($request->selected_ids as $commentId) {
                $komentar = Komentar::find($commentId);
                if ($komentar) {
                    if (!$artikelId) {
                        $artikelId = $komentar->id_artikel;
                    }
                    $komentar->delete();
                    $count++;
                }
            }
            
            return redirect()->back()->with('success', $count . ' komentar berhasil dihapus.');
        } else {
            // Single delete
            $komentar = Komentar::findOrFail($id);
            $artikelId = $komentar->id_artikel;
            $komentar->delete();
            
            return redirect()->route('admin.artikel.komentar.index', ['artikelId' => $artikelId])
                ->with('success', 'Komentar berhasil dihapus.');
        }
    }
} 