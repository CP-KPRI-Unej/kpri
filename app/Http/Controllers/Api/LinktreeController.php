<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Linktree;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LinktreeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('role:kpri admin');
    }
    
    /**
     * Get linktree profile data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLinktreeProfile()
    {
        // Get linktree for current user
        $linktree = Linktree::where('user_id', Auth::user()->id_user)->first();
        
        if (!$linktree) {
            // Create a default linktree if none exists
            $linktree = Linktree::create([
                'user_id' => Auth::user()->id_user,
                'title' => 'KPRI Linktree',
                'bio' => '',
                'logo' => null
            ]);
        }
        
        return response()->json($linktree);
    }
    
    /**
     * Get all links for the linktree
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLinks()
    {
        $linktree = Linktree::where('user_id', Auth::user()->id_user)->first();
        
        if (!$linktree) {
            return response()->json([
                'success' => false,
                'message' => 'Linktree not found'
            ], 404);
        }
        
        $links = Link::where('page_id', $linktree->id)
            ->orderBy('position', 'asc')
            ->get();
            
        return response()->json($links);
    }
    
    /**
     * Update linktree profile
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'bio' => 'nullable|string|max:80',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_logo' => 'nullable|boolean'
        ]);
        
        try {
            // Start transaction
            DB::beginTransaction();
            
            $linktree = Linktree::where('user_id', Auth::user()->id_user)->first();
            
            if (!$linktree) {
                // Create a new linktree if none exists
                $linktree = new Linktree();
                $linktree->user_id = Auth::user()->id_user;
            }
            
            $linktree->title = $request->title;
            $linktree->bio = $request->bio ?? '';
            
            // Handle logo removal
            if ($request->has('remove_logo') && $request->remove_logo) {
                if ($linktree->logo && Storage::exists('public/' . $linktree->logo)) {
                    Storage::delete('public/' . $linktree->logo);
                }
                $linktree->logo = null;
            }
            
            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($linktree->logo && Storage::exists('public/' . $linktree->logo)) {
                    Storage::delete('public/' . $linktree->logo);
                }
                
                $path = $request->file('logo')->store('linktree', 'public');
                $linktree->logo = $path;
            }
            
            $linktree->save();
            
            // Commit transaction
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Linktree profile updated successfully',
                'linktree' => $linktree
            ]);
            
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update linktree profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Store a new link
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeLink(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'url' => 'required|url|max:255'
        ]);
        
        try {
            // Start transaction
            DB::beginTransaction();
            
            $linktree = Linktree::where('user_id', Auth::user()->id_user)->first();
            
            if (!$linktree) {
                // Create a new linktree if none exists
                $linktree = Linktree::create([
                    'user_id' => Auth::user()->id_user,
                    'title' => 'KPRI Linktree',
                    'bio' => '',
                    'logo' => null
                ]);
            }
            
            // Get highest position
            $maxPosition = Link::where('page_id', $linktree->id)->max('position') ?? 0;
            
            // Create new link
            $link = new Link();
            $link->page_id = $linktree->id;
            $link->title = $request->title;
            $link->url = $request->url;
            $link->position = $maxPosition + 1;
            $link->save();
            
            // Commit transaction
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Link added successfully',
                'link' => $link
            ], 201);
            
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add link',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update an existing link
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLink(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'url' => 'required|url|max:255'
        ]);
        
        try {
            // Start transaction
            DB::beginTransaction();
            
            $linktree = Linktree::where('user_id', Auth::user()->id_user)->first();
            
            if (!$linktree) {
                return response()->json([
                    'success' => false,
                    'message' => 'Linktree not found'
                ], 404);
            }
            
            $link = Link::where('id', $id)
                ->where('page_id', $linktree->id)
                ->first();
            
            if (!$link) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link not found or access denied'
                ], 404);
            }
            
            $link->title = $request->title;
            $link->url = $request->url;
            $link->save();
            
            // Commit transaction
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Link updated successfully',
                'link' => $link
            ]);
            
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update link',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete a link
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteLink($id)
    {
        try {
            // Start transaction
            DB::beginTransaction();
            
            $linktree = Linktree::where('user_id', Auth::user()->id_user)->first();
            
            if (!$linktree) {
                return response()->json([
                    'success' => false,
                    'message' => 'Linktree not found'
                ], 404);
            }
            
            $link = Link::where('id', $id)
                ->where('page_id', $linktree->id)
                ->first();
            
            if (!$link) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link not found or access denied'
                ], 404);
            }
            
            // Get position of the deleted link
            $deletedPosition = $link->position;
            
            // Delete the link
            $link->delete();
            
            // Update positions of other links
            Link::where('page_id', $linktree->id)
                ->where('position', '>', $deletedPosition)
                ->decrement('position');
            
            // Commit transaction
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Link deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete link',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update link positions
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePositions(Request $request)
    {
        $request->validate([
            'positions' => 'required|array',
            'positions.*' => 'required|integer|exists:links,id'
        ]);
        
        try {
            // Start transaction
            DB::beginTransaction();
            
            $linktree = Linktree::where('user_id', Auth::user()->id_user)->first();
            
            if (!$linktree) {
                return response()->json([
                    'success' => false,
                    'message' => 'Linktree not found'
                ], 404);
            }
            
            // Update positions
            $positions = $request->positions;
            foreach ($positions as $index => $linkId) {
                $position = $index + 1;
                
                Link::where('id', $linkId)
                    ->where('page_id', $linktree->id)
                    ->update(['position' => $position]);
            }
            
            // Commit transaction
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Link positions updated successfully'
            ]);
            
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update link positions',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 