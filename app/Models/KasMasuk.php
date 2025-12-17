<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KasMasuk extends Model
{
    use HasFactory;

    protected $table = 'kas_masuk';

    // Konfigurasi UUID
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kode_kas',
        'tanggal_transaksi',
        'keterangan',
        'kategori',
        'payment_method', // KOLOM DATABASE YANG BENAR
        'detail_items',
        'jumlah',
        'harga_satuan',
        'total',
        'kembalian',
        'user_id',
        'outlet_id',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'total' => 'decimal:2',
        'kembalian' => 'decimal:2',
        'detail_items' => 'array',
    ];

    // --- ACCESSOR ---
    // Memungkinkan pemanggilan $item->metode_pembayaran di Blade View
    // Walaupun kolom aslinya payment_method
    public function getMetodePembayaranAttribute()
    {
        return $this->attributes['payment_method'] ?? 'Tunai';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // 1. Generate UUID
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }

            // 2. Default Payment Method jika kosong
            // Ini yang menyebabkan bug "Tunai" sebelumnya jika Controller salah kirim key
            if (empty($model->payment_method)) {
                $model->payment_method = 'Tunai';
            }

            // 3. Logic Total Otomatis
            if ($model->jumlah && $model->harga_satuan && !$model->total) {
                $model->total = $model->jumlah * $model->harga_satuan;
            }

            // 4. Fallback Kode Kas
            if (empty($model->kode_kas)) {
                $model->kode_kas = 'KM-' . date('Ymd') . '-' . strtoupper(Str::random(3));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function outlet()
    {
        return $this->belongsTo(\App\Models\Outlet::class);
    }
}
