<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Linktree;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LinktreeController extends Controller
{
    /**
     * Display the linktree profile page.
     */
    public function index()
    {
        $user = Auth::user();
        $linktree = Linktree::where('user_id', $user->id_user)->first();
        
        if (!$linktree) {
            // Create default linktree for user
            $linktree = Linktree::create([
                'user_id' => $user->id_user,
                'title' => 'KPRI UNIVERSITAS JEMBER',
                'bio' => 'Bio'
            ]);
        }
        
        $links = Link::where('page_id', $linktree->id)
                    ->orderBy('position', 'asc')
                    ->get();
        
        return view('admin.linktree.index', compact('linktree', 'links'));
    }
    
    /**
     * Update the linktree profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $linktree = Linktree::where('user_id', $user->id_user)->first();
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'bio' => 'nullable|max:80',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
        }
        
        $linktree->title = $request->title;
        $linktree->bio = $request->bio;
        
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($linktree->logo) {
                Storage::delete('public/' . $linktree->logo);
            }
            
            $logoPath = $request->file('logo')->store('linktree', 'public');
            $linktree->logo = $logoPath;
        }
        
        if ($request->has('remove_logo') && $request->remove_logo) {
            Storage::delete('public/' . $linktree->logo);
            $linktree->logo = null;
        }
        
        $linktree->save();
        
        return redirect()->route('admin.linktree.index')->with('success', 'Profile updated successfully');
    }
    
    /**
     * Store a new link.
     */
    public function storeLink(Request $request)
    {
        $user = Auth::user();
        $linktree = Linktree::where('user_id', $user->id_user)->first();
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'url' => 'required|url|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
        }
        
        // Get the highest position
        $maxPosition = Link::where('page_id', $linktree->id)->max('position') ?? 0;
        
        Link::create([
            'page_id' => $linktree->id,
            'title' => $request->title,
            'url' => $request->url,
            'position' => $maxPosition + 1,
        ]);
        
        return redirect()->route('admin.linktree.index')->with('success', 'Link added successfully');
    }
    
    /**
     * Update an existing link.
     */
    public function updateLink(Request $request, $id)
    {
        $user = Auth::user();
        $linktree = Linktree::where('user_id', $user->id_user)->first();
        $link = Link::where('id', $id)->where('page_id', $linktree->id)->firstOrFail();
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'url' => 'required|url|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
        }
        
        $link->title = $request->title;
        $link->url = $request->url;
        $link->save();
        
        return redirect()->route('admin.linktree.index')->with('success', 'Link updated successfully');
    }
    
    /**
     * Delete a link.
     */
    public function deleteLink($id)
    {
        $user = Auth::user();
        $linktree = Linktree::where('user_id', $user->id_user)->first();
        $link = Link::where('id', $id)->where('page_id', $linktree->id)->firstOrFail();
        
        $link->delete();
        
        // Reorder remaining links
        $links = Link::where('page_id', $linktree->id)
                     ->orderBy('position', 'asc')
                     ->get();
        
        $position = 1;
        foreach ($links as $link) {
            $link->position = $position++;
            $link->save();
        }
        
        return redirect()->route('admin.linktree.index')->with('success', 'Link deleted successfully');
    }
    
    /**
     * Update link positions (for drag and drop reordering).
     */
    public function updateLinkPositions(Request $request)
    {
        $user = Auth::user();
        $linktree = Linktree::where('user_id', $user->id_user)->first();
        
        if ($request->has('positions') && is_array($request->positions)) {
            foreach ($request->positions as $position => $linkId) {
                Link::where('id', $linkId)
                    ->where('page_id', $linktree->id)
                    ->update(['position' => $position + 1]);
            }
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 400);
    }
    
    /**
     * Get the linktree logo URL for AJAX requests.
     */
    public function getLogoUrl()
    {
        $user = Auth::user();
        $linktree = Linktree::where('user_id', $user->id_user)->first();
        
        if ($linktree && $linktree->logo) {
            return response()->json([
                'success' => true,
                'logoUrl' => asset('storage/' . $linktree->logo)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'defaultLogoUrl' => asset('images/logo.png')
        ]);
    }
} 