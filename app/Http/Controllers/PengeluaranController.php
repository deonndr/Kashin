<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function create(Kegiatan $kegiatan)
    {
        return view('kegiatan.pengeluaran.create', compact('kegiatan'));
    }

    public function store(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'nama_pengeluaran' => 'required|string|max:255',
            'nominal_keluar'   => 'required|integer|min:1',
            'tanggal_keluar'   => 'nullable|date',
        ], [
            'nama_pengeluaran.required' => 'Nama item pengeluaran wajib diisi.',
            'nominal_keluar.required'   => 'Nominal wajib diisi.',
        ]);

        Pengeluaran::create([
            'kegiatan_id'      => $kegiatan->id,
            'nama_pengeluaran' => $request->nama_pengeluaran,
            'nominal_keluar'   => $request->nominal_keluar,
            'tanggal_keluar'   => $request->tanggal_keluar ?: now()->toDateString(),
        ]);

        return redirect()->route('kegiatan.show', $kegiatan)
            ->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $kegiatanId = $pengeluaran->kegiatan_id;
        $pengeluaran->delete();

        return redirect()->route('kegiatan.show', $kegiatanId)
            ->with('success', 'Item pengeluaran berhasil dihapus.');
    }
}
