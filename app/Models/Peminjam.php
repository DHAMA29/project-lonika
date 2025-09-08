<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjam extends Model
{
    use HasFactory;

    protected $table = 'peminjam';
    protected $fillable = ['nama','email','telepon','alamat'];

    public function peminjaman() {
        return $this->hasMany(Peminjaman::class);
    }
}
