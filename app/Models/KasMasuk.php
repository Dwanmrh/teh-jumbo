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
        // Update Baru:
        'detail_items',
        'kembalian'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'total' => 'decimal:2',
        'kembalian' => 'decimal:2',
        'detail_items' => 'array', // Otomatis convert JSON <-> Array
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // 1. Generate UUID jika belum ada
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }

            // 2. Hitung Total otomatis (Backup jika null, meski biasanya diisi controller)
            if ($model->jumlah && $model->harga_satuan && !$model->total) {
                $model->total = $model->jumlah * $model->harga_satuan;
            }

            // 3. Generate Kode Otomatis: KM-001 (POS punya kode sendiri di controller)
            // Logic ini akan override jika kode_kas belum diisi controller.
            // Karena di PosController kita isi manual 'POS-...', bagian ini aman.
            if (empty($model->kode_kas)) {
                $latest = self::orderBy('created_at', 'desc')->first();
                $number = 1;

                if ($latest && preg_match('/KM-(\d+)/', $latest->kode_kas, $matches)) {
                    $number = intval($matches[1]) + 1;
                }

                $model->kode_kas = 'KM-' . str_pad($number, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
