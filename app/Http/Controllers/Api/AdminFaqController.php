<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminFaqController extends Controller
{
    /**
     * Display a listing of FAQs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $faqs = Faq::with('user:id_user,nama_user')
                ->orderBy('id_faq', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $faqs,
                'message' => 'FAQs retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve FAQs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created FAQ in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:120',
            'deskripsi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $faq = new Faq();
            $faq->id_user = Auth::id();
            $faq->judul = $request->judul;
            $faq->deskripsi = $request->deskripsi;
            $faq->save();

            return response()->json([
                'status' => 'success',
                'data' => $faq,
                'message' => 'FAQ created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create FAQ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified FAQ.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $faq = Faq::with('user:id_user,nama_user')
                ->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $faq,
                'message' => 'FAQ retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'FAQ not found'
            ], 404);
        }
    }

    /**
     * Update the specified FAQ in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:120',
            'deskripsi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $faq = Faq::findOrFail($id);
            
            $faq->judul = $request->judul;
            $faq->deskripsi = $request->deskripsi;
            $faq->save();

            return response()->json([
                'status' => 'success',
                'data' => $faq,
                'message' => 'FAQ updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update FAQ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified FAQ from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $faq = Faq::findOrFail($id);
            $faq->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'FAQ deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete FAQ: ' . $e->getMessage()
            ], 500);
        }
    }
} 
 
 
 
 
 
 
 
 
 
 
 