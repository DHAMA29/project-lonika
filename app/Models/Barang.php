<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $fillable = ['jenis_barang_id','nama','deskripsi','stok','harga_hari','gambar'];

    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarang::class, 'jenis_barang_id');
    }

    public function detailPeminjaman() {
        return $this->hasMany(DetailPeminjaman::class);
    }
}
