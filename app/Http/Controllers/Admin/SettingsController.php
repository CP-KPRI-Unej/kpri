<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display the settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        // Validate the request
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Update the user's password
        $user = Auth::user();
        DB::table('user_KPRI')
            ->where('id_user', $user->id_user)
            ->update(['password' => Hash::make($request->password)]);

        return redirect()->route('admin.settings.index')->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Save the user's theme preference.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveTheme(Request $request)
    {
        $request->validate([
            'theme' => ['required', 'in:dark,light'],
        ]);

        $theme = $request->theme;

        // Store theme preference in session
        session(['theme' => $theme]);

        return response()->json(['success' => true]);
    }
} 