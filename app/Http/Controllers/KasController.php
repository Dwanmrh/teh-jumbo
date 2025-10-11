<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;

class KasController extends Controller
{
    // Kas Masuk
    public function masuk()
    {
        $data = Kas::where('jenis', 'masuk')->get();
        return view('kas-masuk', compact('data'));
    }

    // Kas Keluar
    public function keluar()
    {
        $data = Kas::where('jenis', 'keluar')->get();
        return view('kas-keluar', compact('data'));
    }

    // Simpan data baru (baik kas masuk / keluar)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|string|in:masuk,keluar',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        Kas::create($validated);

        // arahkan kembali ke halaman sesuai jenis
        return $validated['jenis'] === 'masuk'
            ? redirect()->route('kas.masuk')->with('success', 'Kas masuk berhasil ditambahkan.')
            : redirect()->route('kas.keluar')->with('success', 'Kas keluar berhasil ditambahkan.');
    }

    // Edit data
    public function edit($id)
    {
        $kas = Kas::findOrFail($id);
        return $kas->jenis === 'masuk'
            ? view('kas-masuk', compact('kas'))
            : view('kas-keluar', compact('kas'));
    }

    // Update data
    public function update(Request $request, $id)
    {
        $kas = Kas::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|string|in:masuk,keluar',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $kas->update($validated);

        return $validated['jenis'] === 'masuk'
            ? redirect()->route('kas.masuk')->with('success', 'Kas masuk berhasil diperbarui.')
            : redirect()->route('kas.keluar')->with('success', 'Kas keluar berhasil diperbarui.');
    }

    // Hapus data
    public function destroy($id)
    {
        $kas = Kas::findOrFail($id);
        $jenis = $kas->jenis;
        $kas->delete();

        return $jenis === 'masuk'
            ? redirect()->route('kas.masuk')->with('success', 'Kas masuk berhasil dihapus.')
            : redirect()->route('kas.keluar')->with('success', 'Kas keluar berhasil dihapus.');
    }
}
