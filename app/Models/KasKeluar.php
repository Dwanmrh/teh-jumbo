<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KasKeluar extends Model
{
    use HasFactory;

    protected $table = 'kas_keluar';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kode_kas',
        'tanggal',
        'kategori',
        'payment_method', // PERBAIKAN: Sesuai nama kolom DB
        'penerima',
        'nominal',
        'bukti_pembayaran',
        'deskripsi',
        'user_id',
    ];

    // PERBAIKAN: Accessor untuk kompatibilitas View
    public function getMetodePembayaranAttribute()
    {
        return $this->attributes['payment_method'] ?? 'Tunai';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }

            // Kode kas dihandle controller agar urut tanggal, tapi fallback jika null:
            if (empty($model->kode_kas)) {
                $model->kode_kas = 'KK-' . date('ym') . '-' . strtoupper(Str::random(3));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
