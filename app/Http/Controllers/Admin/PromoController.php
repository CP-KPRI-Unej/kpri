<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoKpri;
use App\Models\ProdukKpri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PromoController extends Controller
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
     * Display a listing of the promotions.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $promos = PromoKpri::with(['user', 'produks'])->get();
        
        // Update status based on dates
        foreach ($promos as $promo) {
            $today = Carbon::today();
            
            if ($promo->status != 'nonaktif') {
                if ($today->gt(Carbon::parse($promo->tgl_end))) {
                    $promo->status = 'berakhir';
                    $promo->save();
                } elseif ($today->gte(Carbon::parse($promo->tgl_start)) && $today->lte(Carbon::parse($promo->tgl_end))) {
                    $promo->status = 'aktif';
                    $promo->save();
                }
            }
        }
        
        return view('admin.promo.index', compact('promos'));
    }

    /**
     * Show the form for creating a new promotion.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $produks = ProdukKpri::all();
        
        return view('admin.promo.create', compact('produks'));
    }

    /**
     * Store a newly created promotion in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'judul_promo' => 'required|string|max:120',
            'tgl_start' => 'required|date|after_or_equal:today',
            'tgl_end' => 'required|date|after_or_equal:tgl_start',
            'tipe_diskon' => 'required|in:persen,nominal',
            'nilai_diskon' => 'required|integer|min:1',
            'status' => 'required|in:aktif,nonaktif',
            'produk_ids' => 'required|array|min:1',
            'produk_ids.*' => 'exists:produk_kpri,id_produk',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Additional validation for percentage discount
        if ($request->tipe_diskon === 'persen' && $request->nilai_diskon > 100) {
            return redirect()->back()
                ->withErrors(['nilai_diskon' => 'Diskon persentase tidak boleh lebih dari 100%.'])
                ->withInput();
        }

        // Create promotion
        $promo = new PromoKpri();
        $promo->judul_promo = $request->judul_promo;
        $promo->tgl_start = $request->tgl_start;
        $promo->tgl_end = $request->tgl_end;
        $promo->tipe_diskon = $request->tipe_diskon;
        $promo->nilai_diskon = $request->nilai_diskon;
        $promo->status = $request->status;
        $promo->id_user = Auth::id();
        $promo->save();

        // Attach products
        $promo->produks()->attach($request->produk_ids);

        return redirect()->route('admin.promo.index')
            ->with('success', 'Promo berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified promotion.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $promo = PromoKpri::with('produks')->findOrFail($id);
        $produks = ProdukKpri::all();
        $selectedProduks = $promo->produks->pluck('id_produk')->toArray();
        
        return view('admin.promo.edit', compact('promo', 'produks', 'selectedProduks'));
    }

    /**
     * Update the specified promotion in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $promo = PromoKpri::findOrFail($id);
        
        // Different validation for dates based on status
        $dateValidation = 'required|date';
        if ($promo->status !== 'berakhir') {
            $dateValidation = 'required|date|after_or_equal:today';
        }
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'judul_promo' => 'required|string|max:120',
            'tgl_start' => $dateValidation,
            'tgl_end' => 'required|date|after_or_equal:tgl_start',
            'tipe_diskon' => 'required|in:persen,nominal',
            'nilai_diskon' => 'required|integer|min:1',
            'status' => 'required|in:aktif,nonaktif',
            'produk_ids' => 'required|array|min:1',
            'produk_ids.*' => 'exists:produk_kpri,id_produk',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Additional validation for percentage discount
        if ($request->tipe_diskon === 'persen' && $request->nilai_diskon > 100) {
            return redirect()->back()
                ->withErrors(['nilai_diskon' => 'Diskon persentase tidak boleh lebih dari 100%.'])
                ->withInput();
        }

        // Update promotion
        $promo->judul_promo = $request->judul_promo;
        $promo->tgl_start = $request->tgl_start;
        $promo->tgl_end = $request->tgl_end;
        $promo->tipe_diskon = $request->tipe_diskon;
        $promo->nilai_diskon = $request->nilai_diskon;
        $promo->status = $request->status;
        $promo->save();

        // Sync products
        $promo->produks()->sync($request->produk_ids);

        return redirect()->route('admin.promo.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    /**
     * Update the status of a promotion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $promo = PromoKpri::findOrFail($id);
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Can't change status if already ended
        if ($promo->status === 'berakhir') {
            return redirect()->route('admin.promo.index')
                ->with('error', 'Promo yang sudah berakhir tidak dapat diubah statusnya.');
        }

        // Update status
        $promo->status = $request->status;
        $promo->save();

        return redirect()->route('admin.promo.index')
            ->with('success', 'Status promo berhasil diperbarui.');
    }

    /**
     * Remove the specified promotion from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $promo = PromoKpri::findOrFail($id);
        
        // Delete promotion (will detach products due to cascade)
        $promo->delete();
        
        return redirect()->route('admin.promo.index')
            ->with('success', 'Promo berhasil dihapus.');
    }
} 