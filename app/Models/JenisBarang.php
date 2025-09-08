<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisBarang extends Model
{
    use HasFactory;

    protected $table = 'jenis_barang';
    protected $fillable = ['nama'];

    public function barang() {
        return $this->hasMany(Barang::class, 'jenis_id');
    }
}
