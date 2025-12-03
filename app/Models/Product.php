<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'user_id',   // Wajib ada
        'nama',
        'kategori',
        'ukuran',    // Tambahan sesuai Index
        'harga',
        'modal',     // Tambahan sesuai Index
        'stok',
        'foto',
    ];

    // Relasi ke User (Opsional tapi bagus untuk struktur)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
