<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;

class LaporanController extends Controller
{
    public function index()
    {
        $data = Laporan::all();
        return view('laporan', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        Laporan::create($validated);
        return redirect()->route('laporan.keuangan')->with('success', 'Laporan berhasil dibuat.');
    }

    public function edit($id)
    {
        $laporan = Laporan::findOrFail($id);
        return view('laporan', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $laporan->update($validated);
        return redirect()->route('laporan.keuangan')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Laporan::findOrFail($id)->delete();
        return redirect()->route('laporan.keuangan')->with('success', 'Laporan berhasil dihapus.');
    }
}
