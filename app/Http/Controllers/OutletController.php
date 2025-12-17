<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menggunakan latest() agar data baru muncul di paling atas
        $outlets = Outlet::latest()->get();
        return view('outlets.index', compact('outlets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        Outlet::create($data);

        // PERBAIKAN: Menggunakan Redirect agar halaman refresh dan alert muncul
        return redirect()->route('outlets.index')
            ->with('success', 'Outlet berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Outlet $outlet)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $outlet->update($data);

        // PERBAIKAN: Menggunakan Redirect dengan pesan sukses
        return redirect()->route('outlets.index')
            ->with('success', 'Outlet berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outlet $outlet)
    {
        $outlet->delete();

        return redirect()->route('outlets.index')
            ->with('success', 'Outlet berhasil dihapus.');
    }

    /**
     * Hapus Banyak Outlet Sekaligus (Bulk Delete)
     * Dipanggil dari Floating Action Bar
     */
    public function bulkDestroy(Request $request)
    {
        // Validasi input array ID
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:outlets,id',
        ]);

        // Hapus data berdasarkan array ID
        Outlet::whereIn('id', $request->ids)->delete();

        return redirect()->back()
            ->with('success', count($request->ids) . ' Outlet berhasil dihapus.');
    }

    // Endpoint create() & edit() tidak dipakai karena modal
    public function create() { abort(404); }
    public function edit() { abort(404); }
}