<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Linktree;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Admin Linktree",
 *     description="API Endpoints for Linktree management"
 * )
 */
class AdminLinktreeController extends Controller
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
     * @OA\Get(
     *     path="/admin/linktree/profile",
     *     summary="Get Linktree profile",
     *     description="Retrieves or creates a Linktree profile for the authenticated user",
     *     tags={"Admin Linktree"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="KPRI Linktree"),
     *             @OA\Property(property="bio", type="string", example="Official KPRI Linktree"),
     *             @OA\Property(property="logo", type="string", nullable=true, example="linktree/logo.png"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/admin/linktree/links",
     *     summary="Get all links",
     *     description="Retrieves all links for the authenticated user's Linktree",
     *     tags={"Admin Linktree"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="page_id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="KPRI Website"),
     *                 @OA\Property(property="url", type="string", example="https://kpri.com"),
     *                 @OA\Property(property="position", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Linktree not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
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
     * @OA\Post(
     *     path="/admin/linktree/profile",
     *     summary="Update Linktree profile",
     *     description="Updates the Linktree profile for the authenticated user",
     *     tags={"Admin Linktree"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", example="My KPRI Linktree", description="Linktree title"),
     *                 @OA\Property(property="bio", type="string", example="Official KPRI Links", description="Short bio text"),
     *                 @OA\Property(property="logo", type="string", format="binary", description="Logo image file"),
     *                 @OA\Property(property="remove_logo", type="boolean", example=false, description="Whether to remove existing logo")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Linktree profile updated successfully"),
     *             @OA\Property(property="linktree", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="My KPRI Linktree"),
     *                 @OA\Property(property="bio", type="string", example="Official KPRI Links"),
     *                 @OA\Property(property="logo", type="string", nullable=true, example="linktree/logo.png"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to update linktree profile"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
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
     * @OA\Post(
     *     path="/admin/linktree/links",
     *     summary="Create a new link",
     *     description="Creates a new link in the authenticated user's Linktree",
     *     tags={"Admin Linktree"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "url"},
     *             @OA\Property(property="title", type="string", example="KPRI Website", description="Link title"),
     *             @OA\Property(property="url", type="string", example="https://kpri.com", description="Link URL")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Link added successfully"),
     *             @OA\Property(property="link", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="page_id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="KPRI Website"),
     *                 @OA\Property(property="url", type="string", example="https://kpri.com"),
     *                 @OA\Property(property="position", type="integer", example=1),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to add link"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
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
     * @OA\Put(
     *     path="/admin/linktree/links/{id}",
     *     summary="Update a link",
     *     description="Updates an existing link in the authenticated user's Linktree",
     *     tags={"Admin Linktree"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Link ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "url"},
     *             @OA\Property(property="title", type="string", example="Updated KPRI Website", description="Link title"),
     *             @OA\Property(property="url", type="string", example="https://kpri.com/new", description="Link URL")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Link updated successfully"),
     *             @OA\Property(property="link", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="page_id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Updated KPRI Website"),
     *                 @OA\Property(property="url", type="string", example="https://kpri.com/new"),
     *                 @OA\Property(property="position", type="integer", example=1),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Link not found or access denied")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to update link"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/admin/linktree/links/{id}",
     *     summary="Delete a link",
     *     description="Deletes a link from the authenticated user's Linktree",
     *     tags={"Admin Linktree"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Link ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Link deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Link not found or access denied")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to delete link"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
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
     * @OA\Post(
     *     path="/admin/linktree/links/positions",
     *     summary="Update link positions",
     *     description="Updates the positions of links in the authenticated user's Linktree",
     *     tags={"Admin Linktree"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"positions"},
     *             @OA\Property(
     *                 property="positions",
     *                 type="array",
     *                 description="Array of link IDs in the desired order",
     *                 @OA\Items(type="integer"),
     *                 example={3, 1, 4, 2}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Link positions updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Linktree not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to update link positions"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
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
