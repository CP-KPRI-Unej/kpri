<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;

/**
 * @OA\Tag(
 *     name="Admin Settings",
 *     description="API Endpoints for Admin Profile and Settings management"
 * )
 */
class AdminSettingsController extends Controller
{
    /**
     * Get the authenticated user's profile
     * 
     * @OA\Get(
     *     path="/admin/settings/profile",
     *     summary="Get admin profile",
     *     description="Retrieve the authenticated user's profile information",
     *     tags={"Admin Settings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nama", type="string", example="Admin User"),
     *                 @OA\Property(property="username", type="string", example="admin"),
     *                 @OA\Property(property="role", type="string", example="Administrator"),
     *                 @OA\Property(property="last_login", type="string", format="date-time", example="2023-07-15 14:30:00")
     *             ),
     *             @OA\Property(property="message", type="string", example="Profile retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve profile")
     *         )
     *     )
     * )
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile()
    {
        try {
            $user = Auth::user();
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $user->id_user,
                    'nama' => $user->nama_user,
                    'username' => $user->username,
                    'role' => $user->role->nama_role,
                    'last_login' => now()->format('Y-m-d H:i:s')
                ],
                'message' => 'Profile retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve profile: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the authenticated user's profile
     * 
     * @OA\Post(
     *     path="/admin/settings/profile",
     *     summary="Update admin profile",
     *     description="Update the authenticated user's profile information",
     *     tags={"Admin Settings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_user", "username"},
     *             @OA\Property(property="nama_user", type="string", example="Updated Name", description="User's full name"),
     *             @OA\Property(property="username", type="string", example="newusername", description="User's username")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nama", type="string", example="Updated Name"),
     *                 @OA\Property(property="username", type="string", example="newusername"),
     *                 @OA\Property(property="role", type="string", example="Administrator")
     *             ),
     *             @OA\Property(property="message", type="string", example="Profile updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(
     *                 property="errors", 
     *                 type="object",
     *                 @OA\Property(property="nama_user", type="array", @OA\Items(type="string", example="The nama user field is required.")),
     *                 @OA\Property(property="username", type="array", @OA\Items(type="string", example="The username has already been taken."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to update profile")
     *         )
     *     )
     * )
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'nama_user' => 'required|string|max:100',
            'username' => 'required|string|max:20|unique:user_KPRI,username,' . $user->id_user . ',id_user',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $oldData = [
                'nama_user' => $user->nama_user,
                'username' => $user->username
            ];
            
            $user->nama_user = $request->nama_user;
            $user->username = $request->username;
            $user->save();
            
            // Log activity
            $this->logActivity(
                'user_KPRI', 
                $user->id_user, 
                'update', 
                $oldData, 
                ['nama_user' => $user->nama_user, 'username' => $user->username]
            );

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $user->id_user,
                    'nama' => $user->nama_user,
                    'username' => $user->username,
                    'role' => $user->role->nama_role
                ],
                'message' => 'Profile updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update profile: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the authenticated user's password
     * 
     * @OA\Post(
     *     path="/admin/settings/password",
     *     summary="Update admin password",
     *     description="Update the authenticated user's password",
     *     tags={"Admin Settings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"current_password", "new_password", "new_password_confirmation"},
     *             @OA\Property(property="current_password", type="string", format="password", example="current123", description="Current password"),
     *             @OA\Property(property="new_password", type="string", format="password", example="newpassword123", description="New password"),
     *             @OA\Property(property="new_password_confirmation", type="string", format="password", example="newpassword123", description="Confirm new password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Password updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error or Invalid Current Password",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Current password is incorrect"
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="current_password", type="array", @OA\Items(type="string", example="The current password field is required.")),
     *                 @OA\Property(property="new_password", type="array", @OA\Items(type="string", example="The new password must be at least 8 characters."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to update password")
     *         )
     *     )
     * )
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Current password is incorrect'
                ], 422);
            }
            
            // Update password
            $user->password = Hash::make($request->new_password);
            $user->save();
            
            // Log activity (don't log the actual passwords)
            $this->logActivity(
                'user_KPRI', 
                $user->id_user, 
                'password_change', 
                ['password' => '********'], 
                ['password' => '********']
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Password updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update password: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update password: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Log activity for audit trail
     * 
     * @param string $table
     * @param int $dataId
     * @param string $action
     * @param array $oldData
     * @param array $newData
     */
    private function logActivity($table, $dataId, $action, $oldData = null, $newData = null)
    {
        try {
            $user = Auth::user();
            
            \DB::table('log_perubahan')->insert([
                'id_user' => $user->id_user,
                'nama_tabel' => $table,
                'id_data' => $dataId,
                'aksi' => $action,
                'tgl_log' => now(),
                'data_lama' => $oldData ? json_encode($oldData) : null,
                'data_baru' => $newData ? json_encode($newData) : null
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }
} 
 
 
 
 
 
 
 
 
 
 
 