<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Models\DownloadItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Downloads",
 *     description="API Endpoints for Download Items"
 * )
 */
class DownloadItemController extends Controller
{
    /**
     * Display a listing of download items.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/downloads",
     *     summary="Get all download items",
     *     description="Returns list of all active download items",
     *     operationId="getDownloadItems",
     *     tags={"Downloads"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Financial Report 2023"),
     *                     @OA\Property(property="file_url", type="string", example="https://example.com/storage/financial_report_2023.pdf"),
     *                     @OA\Property(property="file_extension", type="string", example="pdf"),
     *                     @OA\Property(property="upload_date", type="string", format="date", example="2023-12-31"),
     *                     @OA\Property(property="order", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index()
    {
        $downloadItems = DownloadItem::orderBy('urutan', 'asc')
            ->where('status', 'Active')
            ->get([
                'id_download_item',
                'nama_item',
                'path_file',
                'tgl_upload',
                'urutan'
            ]);

        $items = $downloadItems->map(function($item) {
            $fileUrl = url('storage/' . $item->path_file);
            $extension = pathinfo($item->path_file, PATHINFO_EXTENSION);
            
            return [
                'id' => $item->id_download_item,
                'name' => $item->nama_item,
                'file_url' => $fileUrl,
                'file_extension' => $extension,
                'upload_date' => $item->tgl_upload,
                'order' => $item->urutan
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    /**
     * Display the specified download item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/downloads/{id}",
     *     summary="Get specific download item",
     *     description="Returns a specific download item by ID",
     *     operationId="getDownloadItem",
     *     tags={"Downloads"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Download Item ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Financial Report 2023"),
     *                 @OA\Property(property="file_url", type="string", example="https://example.com/storage/financial_report_2023.pdf"),
     *                 @OA\Property(property="file_extension", type="string", example="pdf"),
     *                 @OA\Property(property="upload_date", type="string", format="date", example="2023-12-31"),
     *                 @OA\Property(property="order", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Download item not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function show($id)
    {
        $item = DownloadItem::where('id_download_item', $id)
            ->where('status', 'Active')
            ->firstOrFail();
            
        $fileUrl = url('storage/' . $item->path_file);
        $extension = pathinfo($item->path_file, PATHINFO_EXTENSION);
            
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $item->id_download_item,
                'name' => $item->nama_item,
                'file_url' => $fileUrl,
                'file_extension' => $extension,
                'upload_date' => $item->tgl_upload,
                'order' => $item->urutan
            ]
        ]);
    }
} 