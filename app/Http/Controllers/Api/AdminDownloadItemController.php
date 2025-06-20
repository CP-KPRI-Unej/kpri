<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DownloadItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Admin Downloads",
 *     description="API Endpoints for Admin Download Management"
 * )
 */
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
     * 
     * @OA\Get(
     *     path="/admin/downloads",
     *     summary="Get all download items",
     *     description="Returns a list of all download items sorted by order",
     *     operationId="adminGetDownloadItems",
     *     tags={"Admin Downloads"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id_download_item", type="integer", example=1),
     *                     @OA\Property(property="nama_item", type="string", example="Financial Report 2023"),
     *                     @OA\Property(property="path_file", type="string", example="downloads/financial_report_2023.pdf"),
     *                     @OA\Property(property="id_status", type="integer", example=1),
     *                     @OA\Property(property="id_user", type="integer", example=1),
     *                     @OA\Property(property="tgl_upload", type="string", format="date", example="2023-12-31"),
     *                     @OA\Property(property="urutan", type="integer", example=1),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                     @OA\Property(property="user", type="object"),
     *                     @OA\Property(property="status", type="object")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index()
    {
        $downloadItems = DownloadItem::with(['user', 'status'])
            ->orderBy('urutan', 'asc')
            ->get();
        
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
     * 
     * @OA\Post(
     *     path="/admin/downloads",
     *     summary="Create a new download item",
     *     description="Creates a new download item with file upload",
     *     operationId="adminCreateDownloadItem",
     *     tags={"Admin Downloads"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"nama_item", "file", "id_status"},
     *                 @OA\Property(
     *                     property="nama_item", 
     *                     type="string", 
     *                     example="Annual Report 2023",
     *                     description="Name of the download item"
     *                 ),
     *                 @OA\Property(
     *                     property="file", 
     *                     type="string", 
     *                     format="binary",
     *                     description="File to upload (max 10MB)"
     *                 ),
     *                 @OA\Property(
     *                     property="id_status", 
     *                     type="integer", 
     *                     example=1,
     *                     description="Status ID"
     *                 ),
     *                 @OA\Property(
     *                     property="urutan", 
     *                     type="integer", 
     *                     example=1,
     *                     description="Display order"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Download item created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Download item created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_item' => 'required|string|max:120',
            'file' => 'required|file|max:10240', // Max 10MB
            'id_status' => 'required|exists:status,id_status',
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
        $downloadItem->id_status = $request->id_status;
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
     * 
     * @OA\Get(
     *     path="/admin/downloads/{id}",
     *     summary="Get download item by ID",
     *     description="Returns a specific download item by ID",
     *     operationId="adminGetDownloadItem",
     *     tags={"Admin Downloads"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Download item ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_download_item", type="integer", example=1),
     *                 @OA\Property(property="nama_item", type="string", example="Financial Report 2023"),
     *                 @OA\Property(property="path_file", type="string", example="downloads/financial_report_2023.pdf"),
     *                 @OA\Property(property="id_status", type="integer", example=1),
     *                 @OA\Property(property="id_user", type="integer", example=1),
     *                 @OA\Property(property="tgl_upload", type="string", format="date", example="2023-12-31"),
     *                 @OA\Property(property="urutan", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-31T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Download item not found")
     * )
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
     * 
     * @OA\Post(
     *     path="/admin/downloads/{id}",
     *     summary="Update a download item",
     *     description="Updates an existing download item by ID",
     *     operationId="adminUpdateDownloadItem",
     *     tags={"Admin Downloads"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Download item ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="_method",
     *         in="query",
     *         description="HTTP method override",
     *         required=true,
     *         @OA\Schema(type="string", default="PUT")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="nama_item", 
     *                     type="string", 
     *                     example="Updated Report 2023",
     *                     description="Name of the download item"
     *                 ),
     *                 @OA\Property(
     *                     property="file", 
     *                     type="string", 
     *                     format="binary",
     *                     description="New file to upload (max 10MB)"
     *                 ),
     *                 @OA\Property(
     *                     property="id_status", 
     *                     type="integer", 
     *                     example=1,
     *                     description="Status ID"
     *                 ),
     *                 @OA\Property(
     *                     property="urutan", 
     *                     type="integer", 
     *                     example=2,
     *                     description="Display order"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Download item updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Download item updated successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Download item not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
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
            'id_status' => 'nullable|exists:status,id_status',
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
        
        if ($request->has('id_status')) {
            $downloadItem->id_status = $request->id_status;
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
     * 
     * @OA\Delete(
     *     path="/admin/downloads/{id}",
     *     summary="Delete a download item",
     *     description="Deletes a download item and its associated file",
     *     operationId="adminDeleteDownloadItem",
     *     tags={"Admin Downloads"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Download item ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Download item deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Download item deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Download item not found")
     * )
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
     * 
     * @OA\Put(
     *     path="/admin/downloads/order",
     *     summary="Update download items order",
     *     description="Updates the display order of multiple download items",
     *     operationId="adminUpdateDownloadItemsOrder",
     *     tags={"Admin Downloads"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"items"},
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"id", "urutan"},
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="urutan", type="integer", example=2)
     *                 ),
     *                 example={
     *                     {"id": 1, "urutan": 3},
     *                     {"id": 2, "urutan": 1},
     *                     {"id": 3, "urutan": 2}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Download items order updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Download items order updated successfully"),
     *             @OA\Property(property="updated_count", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="No items were updated"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
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