<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DiskonModel extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     */
    protected $table = 'diskon';
    
    /**
     * Allow mass assignment for all attributes
     */
    protected $guarded = [];
    
    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'persentase' => 'decimal:2',
        'tanggal_mulai' => 'datetime',
        'tanggal_berakhir' => 'datetime',
        'batas_penggunaan' => 'integer',
        'jumlah_terpakai' => 'integer'
    ];

    /**
     * Get the peminjaman records for this discount.
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'kode_diskon', 'kode_diskon');
    }

    /**
     * Check if the discount is currently active.
     */
    public function isActive()
    {
        $now = now();
        
        return $this->status === 'aktif' 
            && ($this->tanggal_mulai === null || $this->tanggal_mulai <= $now)
            && ($this->tanggal_berakhir === null || $this->tanggal_berakhir >= $now)
            && ($this->batas_penggunaan === null || $this->jumlah_terpakai < $this->batas_penggunaan);
    }

    /**
     * Check if the discount can be used.
     */
    public function canBeUsed()
    {
        return $this->isActive();
    }

    /**
     * Increment the usage count.
     */
    public function incrementUsage()
    {
        $this->increment('jumlah_terpakai');
    }
    
    /**
     * Generate a random discount code.
     */
    public static function generateRandomCode()
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (DB::table('diskon')->where('kode_diskon', $code)->exists());
        
        return $code;
    }
}
