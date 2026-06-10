<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PembayaranIuran;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;

    protected $table = "siswa";

    protected $fillable = ["user_id", "nisn", "nama_siswa", "kelas"];

    public function pembayaranIurans()
    {
        return $this->hasMany(PembayaranIuran::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
