<?php

namespace App\Models;

use App\Models\Siswa;
use App\Models\PeriodeIuran;
use Illuminate\Database\Eloquent\Model;

class PembayaranIuran extends Model
{
    protected $table = "pembayaran_iuran";

    protected $fillable = ["siswa_id", "periode_iuran_id", "jumlah_bayar", "tanggal_bayar", "status_bayar"];

    public function siswa() {
        return $this->belongsTo(Siswa::class);
    }

    public function periodeIuran() {
        return $this->belongsTo(PeriodeIuran::class);
    }
}
