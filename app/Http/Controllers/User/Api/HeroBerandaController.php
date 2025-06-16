<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroBeranda;
use Illuminate\Http\Request;

class HeroBerandaController extends Controller
{
    /**
     * Display a listing of all active hero banners for the front page.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $heroes = HeroBeranda::orderBy('id_hero', 'desc')->get();

            return response()->json([
                'status' => 'success',
                'data' => $heroes,
                'message' => 'Hero banners retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve hero banners: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified hero banner.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $hero = HeroBeranda::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $hero,
                'message' => 'Hero banner retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hero banner not found'
            ], 404);
        }
    }
} 