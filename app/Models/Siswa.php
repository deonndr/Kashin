<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PembayaranIuran;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;
    protected $table = "siswa";
    protected $fillable = ["nisn","nama_siswa","kelas"];

    public function pembayaranIurans() {
        return $this->hasMany(PembayaranIuran::class);
    }
}
