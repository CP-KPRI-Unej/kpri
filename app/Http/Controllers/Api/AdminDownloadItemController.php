<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DownloadItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AdminDownloadItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('role:kpri admin');
    }
    
    /**
     * Display a listing of the download items.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $downloadItems = DownloadItem::orderBy('urutan', 'asc')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $downloadItems
        ]);
    }

    /**
     * Store a newly created download item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_item' => 'required|string|max:120',
            'file' => 'required|file|max:10240', // Max 10MB
            'status' => 'required|in:Active,Inactive',
            'urutan' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Store the file
        $file = $request->file('file');
        $filePath = $file->store('downloads', 'public');

        // Create new download item
        $downloadItem = new DownloadItem();
        $downloadItem->id_user = Auth::id();
        $downloadItem->nama_item = $request->nama_item;
        $downloadItem->path_file = $filePath;
        $downloadItem->status = $request->status;
        $downloadItem->tgl_upload = now()->format('Y-m-d');
        $downloadItem->urutan = $request->urutan ?? 0;
        $downloadItem->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Download item created successfully',
            'data' => $downloadItem
        ], 201);
    }

    /**
     * Display the specified download item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $downloadItem = DownloadItem::find($id);
        
        if (!$downloadItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Download item not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $downloadItem
        ]);
    }

    /**
     * Update the specified download item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $downloadItem = DownloadItem::find($id);
        
        if (!$downloadItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Download item not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_item' => 'nullable|string|max:120',
            'file' => 'nullable|file|max:10240', // Max 10MB
            'status' => 'nullable|in:Active,Inactive',
            'urutan' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update file if provided
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($downloadItem->path_file && Storage::disk('public')->exists($downloadItem->path_file)) {
                Storage::disk('public')->delete($downloadItem->path_file);
            }
            
            // Store new file
            $file = $request->file('file');
            $filePath = $file->store('downloads', 'public');
            $downloadItem->path_file = $filePath;
        }

        // Update other fields
        if ($request->has('nama_item')) {
            $downloadItem->nama_item = $request->nama_item;
        }
        
        if ($request->has('status')) {
            $downloadItem->status = $request->status;
        }
        
        if ($request->has('urutan')) {
            $downloadItem->urutan = $request->urutan;
        }

        $downloadItem->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Download item updated successfully',
            'data' => $downloadItem
        ]);
    }

    /**
     * Remove the specified download item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $downloadItem = DownloadItem::find($id);
        
        if (!$downloadItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Download item not found'
            ], 404);
        }

        // Delete file from storage
        if ($downloadItem->path_file && Storage::disk('public')->exists($downloadItem->path_file)) {
            Storage::disk('public')->delete($downloadItem->path_file);
        }

        $downloadItem->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Download item deleted successfully'
        ]);
    }

    /**
     * Update the order of download items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.urutan' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updatedCount = 0;
            
            foreach ($request->items as $item) {
                // Use the primary key id_download_item
                $downloadItem = DownloadItem::where('id_download_item', $item['id'])->first();
                
                if ($downloadItem) {
                    $downloadItem->urutan = $item['urutan'];
                    $downloadItem->save();
                    $updatedCount++;
                } else {
                    // Log not found items for debugging
                    Log::warning("Download item not found with ID: {$item['id']}");
                }
            }
            
            if ($updatedCount > 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Download items order updated successfully',
                    'updated_count' => $updatedCount
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No items were updated. Please check that the item IDs are correct.'
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error("Error updating download items order: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating items: ' . $e->getMessage()
            ], 500);
        }
    }
} 