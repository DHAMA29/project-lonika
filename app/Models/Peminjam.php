<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjam extends Model
{
    use HasFactory;

    protected $table = 'peminjam';
    protected $fillable = ['nama','email','telepon','alamat'];

    /**
     * Validation rules for preventing duplicates
     */
    public static function validationRules($id = null)
    {
        return [
            'nama' => 'required|string|max:255|unique:peminjam,nama' . ($id ? ",{$id}" : ''),
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:20|unique:peminjam,telepon' . ($id ? ",{$id}" : ''),
            'alamat' => 'required|string|max:500',
        ];
    }

    /**
     * Custom error messages for validation
     */
    public static function validationMessages()
    {
        return [
            'nama.unique' => 'Nama ini sudah terdaftar dalam sistem.',
            'telepon.unique' => 'Nomor telepon ini sudah terdaftar dalam sistem.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ];
    }

    public function peminjaman() {
        return $this->hasMany(Peminjaman::class);
    }
}
