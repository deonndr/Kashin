<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::withSum('pengeluaran', 'nominal_keluar')
            ->withCount('pengeluaran')
            ->latest()
            ->get();

        return view('kegiatan.index', compact('kegiatans'));
    }

    public function create()
    {
        return view('kegiatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:50',
            'estimasi_biaya'=> 'required|integer|min:0',
        ], [
            'nama_kegiatan.required'  => 'Nama kegiatan wajib diisi.',
            'estimasi_biaya.required' => 'Estimasi biaya wajib diisi.',
        ]);

        Kegiatan::create($request->only('nama_kegiatan', 'estimasi_biaya'));

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load('pengeluaran');
        $totalRealisasi = $kegiatan->pengeluaran->sum('nominal_keluar');
        $pct = $kegiatan->estimasi_biaya > 0
            ? min(100, round($totalRealisasi / $kegiatan->estimasi_biaya * 100))
            : 0;

        return view('kegiatan.show', compact('kegiatan', 'totalRealisasi', 'pct'));
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();
        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
