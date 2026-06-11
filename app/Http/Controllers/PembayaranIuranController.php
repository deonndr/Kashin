<?php

namespace App\Http\Controllers;

use App\Models\PembayaranIuran;
use App\Models\PeriodeIuran;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PembayaranIuranController extends Controller
{
    public function index(Request $request)
    {
        $query = PembayaranIuran::with(['siswa', 'periodeIuran'])->latest('tanggal_bayar');

        if ($request->filled('periode')) {
            $query->where('periode_iuran_id', $request->periode);
        }
        if ($request->filled('status')) {
            $query->where('status_bayar', $request->status);
        }

        $pembayarans = $query->get();
        $periodes    = PeriodeIuran::orderBy('nama_periode')->get();

        return view('pembayaran-iuran.index', compact('pembayarans', 'periodes'));
    }

    public function create()
    {
        $now = Carbon::now();

        // ID siswa yang sudah ada record pembayaran bulan ini
        $sudahBayarIds = PembayaranIuran::whereMonth('tanggal_bayar', $now->month)
            ->whereYear('tanggal_bayar', $now->year)
            ->pluck('siswa_id')
            ->unique();

        // Grup 1: belum bayar bulan ini → tampil paling atas, A-Z
        $belumBayar = Siswa::whereNotIn('id', $sudahBayarIds)
            ->orderBy('nama_siswa')
            ->get();

        // Grup 2: sudah bayar bulan ini → tampil di bawah, A-Z
        $sudahBayar = Siswa::whereIn('id', $sudahBayarIds)
            ->orderBy('nama_siswa')
            ->get();

        $periodes = PeriodeIuran::orderBy('nama_periode')->get();

        return view('pembayaran-iuran.create', compact('belumBayar', 'sudahBayar', 'periodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id'         => 'required|exists:siswa,id',
            'periode_iuran_id' => [
                'required',
                'exists:periode_iuran,id',
                // Cegah duplikasi: satu siswa hanya boleh 1 record per periode
                Rule::unique('pembayaran_iuran')->where(function ($query) use ($request) {
                    return $query->where('siswa_id', $request->siswa_id);
                }),
            ],
            'jumlah_bayar'    => 'required|integer|min:1000',
            'tanggal_bayar'   => 'required|date',
            'status_bayar'    => 'required|in:Lunas,Belum Lunas',
        ], [
            'siswa_id.required'             => 'Pilih siswa terlebih dahulu.',
            'periode_iuran_id.required'     => 'Pilih periode iuran.',
            'periode_iuran_id.unique'       => 'Siswa ini sudah memiliki catatan pembayaran untuk periode tersebut.',
            'jumlah_bayar.required'         => 'Nominal bayar wajib diisi.',
            'tanggal_bayar.required'        => 'Tanggal bayar wajib diisi.',
        ]);

        PembayaranIuran::create($request->only(
            'siswa_id', 'periode_iuran_id', 'jumlah_bayar', 'tanggal_bayar', 'status_bayar'
        ));

        return redirect()->route('pembayaran-iuran.index')
            ->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function destroy(PembayaranIuran $pembayaranIuran)
    {
        $pembayaranIuran->delete();
        return redirect()->route('pembayaran-iuran.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
