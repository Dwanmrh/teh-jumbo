<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'nama',
        'kategori',
        'harga',
        'stok',
        'foto',
    ];

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }

}
