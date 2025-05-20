<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisLayanan;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LayananController extends Controller
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
     * Display a listing of the services for a page.
     *
     * @param int $id_jenis_layanan
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($id_jenis_layanan)
    {
        $jenisLayanan = JenisLayanan::with('layanans')->findOrFail($id_jenis_layanan);
        
        // If there are services, go directly to edit the first one
        if ($jenisLayanan->layanans->count() > 0) {
            $firstLayanan = $jenisLayanan->layanans->first();
            return redirect()->route('admin.layanan.edit', [
                'id_jenis_layanan' => $id_jenis_layanan,
                'id' => $firstLayanan->id_layanan
            ]);
        }
        
        return view('admin.layanan.index', compact('jenisLayanan'));
    }

    /**
     * Show the form for editing the specified service.
     *
     * @param  int  $id_jenis_layanan
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id_jenis_layanan, $id)
    {
        $jenisLayanan = JenisLayanan::findOrFail($id_jenis_layanan);
        $layanan = Layanan::where('id_jenis_layanan', $id_jenis_layanan)
            ->where('id_layanan', $id)
            ->firstOrFail();
        
        return view('admin.layanan.edit', compact('jenisLayanan', 'layanan'));
    }

    /**
     * Update the specified service description in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id_jenis_layanan
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id_jenis_layanan, $id)
    {
        $jenisLayanan = JenisLayanan::findOrFail($id_jenis_layanan);
        $layanan = Layanan::where('id_jenis_layanan', $id_jenis_layanan)
            ->where('id_layanan', $id)
            ->firstOrFail();
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'deskripsi_layanan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update service description only
        $layanan->update([
            'deskripsi_layanan' => $request->deskripsi_layanan,
        ]);

        return redirect()->route('admin.layanan.index', $id_jenis_layanan)
            ->with('success', 'Deskripsi layanan berhasil diperbarui.');
    }
} 