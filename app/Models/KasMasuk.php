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
        'metode_pembayaran',
        'jumlah',
        'harga_satuan',
        'total',
        'user_id',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // 1. Generate UUID jika belum ada
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }

            // 2. Hitung Total otomatis (Backup)
            if ($model->jumlah && $model->harga_satuan) {
                $model->total = $model->jumlah * $model->harga_satuan;
            }

            // 3. Generate Kode Otomatis: KM-001
            $latest = self::orderBy('created_at', 'desc')->first();
            $number = 1;

            if ($latest && preg_match('/KM-(\d+)/', $latest->kode_kas, $matches)) {
                $number = intval($matches[1]) + 1;
            }

            $model->kode_kas = 'KM-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }
}
