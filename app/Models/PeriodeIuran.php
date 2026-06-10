<?php

namespace App\Models;

use App\Models\PembayaranIuran;
use Illuminate\Database\Eloquent\Model;

class PeriodeIuran extends Model
{
    protected $table = "periode_iuran";
    protected $fillable = ["nama_periode", "nominal_tagihan", "bulan", "tahun"];

    public function pembayaranIurans() {
        return $this->hasMany(PembayaranIuran::class);
    }
}
