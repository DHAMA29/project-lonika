<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $fillable = ['jenis_id','nama','stok','harga_hari','foto'];

    public function jenis() {
        return $this->belongsTo(JenisBarang::class, 'jenis_id');
    }

    public function detailPeminjaman() {
        return $this->hasMany(DetailPeminjaman::class);
    }
}
