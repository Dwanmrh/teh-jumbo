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
        $outlets = Outlet::all();
        return view('outlets.index', compact('outlets'));
    }

    /**
     * Store a newly created resource in storage.
     * Dipanggil via fetch() dari modal Create.
     */
    public function store(Request $request)
    {
        // VALIDASI
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        // SIMPAN DATA
        Outlet::create($data);

        // RESPONSE JSON AGAR TIDAK REDIRECT
        return response()->json([
            'status' => 'success',
            'message' => 'Outlet berhasil ditambahkan.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     * Dipanggil via fetch() dari modal Edit.
     */
    public function update(Request $request, Outlet $outlet)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $outlet->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Outlet berhasil diperbarui.'
        ]);
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
     * Endpoint create() dan edit() tidak digunakan lagi
     * karena kita memakai modal AJAX, bukan halaman terpisah.
     */

    public function create()
    {
        abort(404);
    }

    public function edit()
    {
        abort(404);
    }
}
