<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KasKeluar extends Model
{
    protected $table = 'kas_keluar';
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // 🔹 Buat ID UUID
            $model->id = (string) Str::uuid();

            // 🔹 Buat kode kas otomatis untuk tampilan
            $latest = self::orderBy('created_at', 'desc')->first();
            $number = $latest ? intval(substr($latest->kode_kas, 3)) + 1 : 1;
            $model->kode_kas = 'KK-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }

    protected $fillable = [
        'kode_kas',
        'tanggal',
        'kategori',
        'metode_pembayaran',
        'penerima',
        'nominal',
        'bukti_pembayaran',
        'deskripsi',
    ];
}
