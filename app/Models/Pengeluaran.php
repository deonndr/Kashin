<?php

namespace App\Models;

use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = "pengeluaran";
    protected $fillable = ["kegiatan_id","nama_pengeluaran","nominal_keluar","tanggal_keluar"];

    public function kegiatan() {
        return $this->belongsTo(Kegiatan::class);
    }
}

