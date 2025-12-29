<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'user_id',   // Wajib ada
        'outlet_id', // Tambahan REF-POS-OUTLET
        'nama',
        'kategori',
        'ukuran',    // Tambahan sesuai Index
        'harga',
        'modal',     // Tambahan sesuai Index
        'stok',
        'foto',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Outlet
    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
