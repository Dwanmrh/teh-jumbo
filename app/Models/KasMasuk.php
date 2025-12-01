<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KasMasuk extends Model
{
    use HasFactory;

    protected $table = 'kas_masuk'; // nama tabel di database
    protected $keyType = 'string';  // karena pakai UUID (string)
    public $incrementing = false;   // non auto-increment

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

    /**
     * Event listener saat membuat data baru
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Buat UUID
            $model->id = (string) Str::uuid();

            // Hitung total otomatis
            if ($model->jumlah && $model->harga_satuan) {
                $model->total = $model->jumlah * $model->harga_satuan;
            }

            // Generate kode kas masuk otomatis (KM-001, KM-002, dst)
            $latest = self::orderBy('created_at', 'desc')->first();
            $number = 1;

            if ($latest && preg_match('/KM-(\d+)/', $latest->kode_kas, $matches)) {
                $number = intval($matches[1]) + 1;
            }

            $model->kode_kas = 'KM-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }
}
