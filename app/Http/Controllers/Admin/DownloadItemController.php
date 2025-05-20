<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DownloadItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DownloadItemController extends Controller
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
     * Download a file.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function download($id)
    {
        $downloadItem = DownloadItem::where('id_download_item', $id)
            ->where('status', 'Active')
            ->firstOrFail();
        
        if (!Storage::disk('public')->exists($downloadItem->path_file)) {
            return redirect()->back()
                ->with('error', 'File tidak ditemukan.');
        }
        
        $filePath = storage_path('app/public/' . $downloadItem->path_file);
        
        return response()->download(
            $filePath,
            $downloadItem->nama_item . '.' . pathinfo($downloadItem->path_file, PATHINFO_EXTENSION)
        );
    }

    /**
     * Display a listing of the download items.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $downloadItems = DownloadItem::with('user')
            ->orderBy('urutan', 'asc')
            ->get();
        
        return view('admin.download.index', compact('downloadItems'));
    }

    /**
     * Show the form for creating a new download item.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('admin.download.create');
    }

    /**
     * Store a newly created download item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'nama_item' => 'required|string|max:120',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:10240',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle file upload
        $file = $request->file('file');
        $path = $file->store('downloads', 'public');
        
        // Get the highest order value
        $maxOrder = DownloadItem::max('urutan') ?? 0;

        // Create download item
        $downloadItem = new DownloadItem();
        $downloadItem->nama_item = $request->nama_item;
        $downloadItem->path_file = $path;
        $downloadItem->status = $request->status;
        $downloadItem->id_user = Auth::id();
        $downloadItem->tgl_upload = now()->format('Y-m-d');
        $downloadItem->urutan = $maxOrder + 1; // Set as last item
        $downloadItem->save();

        return redirect()->route('admin.download.index')
            ->with('success', 'Item download berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified download item.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $downloadItem = DownloadItem::findOrFail($id);
        
        return view('admin.download.edit', compact('downloadItem'));
    }

    /**
     * Update the specified download item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'nama_item' => 'required|string|max:120',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:10240',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Find download item
        $downloadItem = DownloadItem::findOrFail($id);
        
        // Handle file upload if new file is provided
        if ($request->hasFile('file')) {
            // Delete old file
            if (Storage::disk('public')->exists($downloadItem->path_file)) {
                Storage::disk('public')->delete($downloadItem->path_file);
            }
            
            // Upload new file
            $file = $request->file('file');
            $path = $file->store('downloads', 'public');
            $downloadItem->path_file = $path;
        }
        
        // Update other fields
        $downloadItem->nama_item = $request->nama_item;
        $downloadItem->status = $request->status;
        $downloadItem->save();

        return redirect()->route('admin.download.index')
            ->with('success', 'Item download berhasil diperbarui.');
    }

    /**
     * Remove the specified download item from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $downloadItem = DownloadItem::findOrFail($id);
        
        // Delete file from storage
        if (Storage::disk('public')->exists($downloadItem->path_file)) {
            Storage::disk('public')->delete($downloadItem->path_file);
        }
        
        // Delete record
        $downloadItem->delete();
        
        return redirect()->route('admin.download.index')
            ->with('success', 'Item download berhasil dihapus.');
    }

    /**
     * Update the order of download items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*' => 'integer|exists:download_item,id_download_item',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Update order for each item
        foreach ($request->items as $index => $id) {
            DownloadItem::where('id_download_item', $id)->update(['urutan' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
} 