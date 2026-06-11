<?php

namespace App\Http\Controllers;

use App\Models\PeriodeIuran;
use Illuminate\Http\Request;

class PeriodeIuranController extends Controller
{
    public function index()
    {
        // Urutkan kronologis: tahun dulu, baru bulan
        $periodes = PeriodeIuran::withCount('pembayaranIurans')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();
        return view('periode-iuran.index', compact('periodes'));
    }

    public function create()
    {
        return view('periode-iuran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan'           => 'required|integer|min:1|max:12',
            'tahun'           => 'required|integer|min:2020|max:2099',
            'nominal_tagihan' => 'required|integer|min:1000',
        ], [
            'bulan.required'           => 'Bulan wajib dipilih.',
            'tahun.required'           => 'Tahun wajib diisi.',
            'nominal_tagihan.required' => 'Nominal tagihan wajib diisi.',
            'nominal_tagihan.min'      => 'Nominal minimal Rp 1.000.',
        ]);

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        // Cek duplikasi bulan+tahun
        $exists = PeriodeIuran::where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'bulan' => 'Periode ' . $namaBulan[$request->bulan] . ' ' . $request->tahun . ' sudah ada.',
            ])->withInput();
        }

        PeriodeIuran::create([
            'nama_periode'    => $namaBulan[$request->bulan] . ' ' . $request->tahun,
            'bulan'           => $request->bulan,
            'tahun'           => $request->tahun,
            'nominal_tagihan' => $request->nominal_tagihan,
        ]);

        return redirect()->route('periode-iuran.index')
            ->with('success', 'Periode iuran berhasil ditambahkan.');
    }

    public function edit(PeriodeIuran $periodeIuran)
    {
        return view('periode-iuran.edit', compact('periodeIuran'));
    }

    public function update(Request $request, PeriodeIuran $periodeIuran)
    {
        $request->validate([
            'nominal_tagihan' => 'required|integer|min:1000',
        ], [
            'nominal_tagihan.required' => 'Nominal tagihan wajib diisi.',
            'nominal_tagihan.min'      => 'Nominal minimal Rp 1.000.',
        ]);

        $periodeIuran->update([
            'nominal_tagihan' => $request->nominal_tagihan,
        ]);

        return redirect()->route('periode-iuran.index')
            ->with('success', "Nominal periode \"{$periodeIuran->nama_periode}\" berhasil diperbarui.");
    }

    public function destroy(PeriodeIuran $periodeIuran)
    {
        // Cegah hapus jika masih ada transaksi pembayaran di periode ini
        if ($periodeIuran->pembayaranIurans()->exists()) {
            return redirect()->route('periode-iuran.index')
                ->with('error', "Periode \"{$periodeIuran->nama_periode}\" tidak bisa dihapus karena masih ada {$periodeIuran->pembayaranIurans()->count()} data pembayaran yang terhubung. Hapus semua pembayaran periode ini terlebih dahulu.");
        }

        $nama = $periodeIuran->nama_periode;
        $periodeIuran->delete();

        return redirect()->route('periode-iuran.index')
            ->with('success', "Periode \"{$nama}\" berhasil dihapus.");
    }
}
