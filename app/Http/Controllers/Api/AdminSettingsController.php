<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AdminSettingsController extends Controller
{
    /**
     * Get the authenticated user's profile
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
 
 
 
 
 
 
 
 
 
 
 