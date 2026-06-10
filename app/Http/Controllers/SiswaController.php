<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::orderBy('nama_siswa')->get();
        return view('siswa.index', compact('siswas'));
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn'       => 'required|digits_between:1,10|unique:siswa,nisn',
            'nama_siswa' => 'required|string|max:100',
            'kelas'      => 'required|string|max:10',
            'email'      => 'required|email|max:255|unique:users,email',
        ], [
            'nisn.required'       => 'NISN wajib diisi.',
            'nisn.digits_between' => 'NISN maksimal 10 digit.',
            'nisn.unique'         => 'NISN sudah terdaftar.',
            'nama_siswa.required' => 'Nama siswa wajib diisi.',
            'kelas.required'      => 'Kelas wajib diisi.',
            'email.required'      => 'Email akun login wajib diisi.',
            'email.unique'        => 'Email sudah digunakan akun lain.',
            'email.email'         => 'Format email tidak valid.',
        ]);

        // Buat akun User terlebih dahulu
        $user = User::create([
            'name'     => $request->nama_siswa,
            'email'    => $request->email,
            'password' => Hash::make('siswa123'),
            'role'     => 'siswa',
        ]);

        // Buat record Siswa yang terhubung ke User
        Siswa::create([
            'user_id'    => $user->id,
            'nisn'       => $request->nisn,
            'nama_siswa' => $request->nama_siswa,
            'kelas'      => $request->kelas,
        ]);

        return redirect()->route('siswa.index')
            ->with('success', "Siswa {$request->nama_siswa} berhasil ditambahkan. Akun login: {$request->email} / password: siswa123");
    }

    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nisn'       => 'required|digits_between:1,10|unique:siswa,nisn,' . $siswa->id,
            'nama_siswa' => 'required|string|max:100',
            'kelas'      => 'required|string|max:10',
        ], [
            'nisn.required'       => 'NISN wajib diisi.',
            'nisn.unique'         => 'NISN sudah terdaftar.',
            'nama_siswa.required' => 'Nama siswa wajib diisi.',
            'kelas.required'      => 'Kelas wajib diisi.',
        ]);

        $siswa->update($request->only('nisn', 'nama_siswa', 'kelas'));

        // Sinkronisasi nama ke akun User yang terhubung
        if ($siswa->user) {
            $siswa->user->update(['name' => $request->nama_siswa]);
        }

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $namaS = $siswa->nama_siswa;

        // Hapus akun User yang terhubung agar tidak jadi akun zombie
        if ($siswa->user) {
            $siswa->user->delete();
        } else {
            $siswa->delete();
        }
        // Jika user dihapus dengan onDelete('set null'), hapus siswa juga manual
        // Tapi karena user->delete() akan set user_id = null di siswa,
        // kita hapus siswa secara eksplisit
        if ($siswa->exists) {
            $siswa->delete();
        }

        return redirect()->route('siswa.index')
            ->with('success', "Siswa {$namaS} dan akun loginnya berhasil dihapus.");
    }
}
