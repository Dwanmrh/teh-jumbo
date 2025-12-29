<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property string $id
 * @property string $kode_kas
 * @property string $tanggal
 * @property string $kategori
 * @property string $payment_method
 * @property string $penerima
 * @property numeric $nominal
 * @property string|null $bukti_pembayaran
 * @property string|null $deskripsi
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $metode_pembayaran
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasKeluar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasKeluar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasKeluar query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasKeluar whereBuktiPembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasKeluar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasKeluar whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasKeluar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasKeluar whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasKeluar whereKodeKas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasKeluar whereNominal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasKeluar wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasKeluar wherePenerima($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasKeluar whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasKeluar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasKeluar whereUserId($value)
 */
	class KasKeluar extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $kode_kas
 * @property \Illuminate\Support\Carbon $tanggal_transaksi
 * @property string|null $keterangan
 * @property string $kategori
 * @property string $payment_method
 * @property array<array-key, mixed>|null $detail_items
 * @property int|null $jumlah
 * @property numeric|null $harga_satuan
 * @property numeric $total
 * @property numeric $kembalian
 * @property int $user_id
 * @property int|null $outlet_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $metode_pembayaran
 * @property-read \App\Models\Outlet|null $outlet
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk whereDetailItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk whereHargaSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk whereKembalian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk whereKodeKas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk whereOutletId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk whereTanggalTransaksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasMasuk whereUserId($value)
 */
	class KasMasuk extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $judul
 * @property string $isi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereUpdatedAt($value)
 */
	class Laporan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outlet whereUpdatedAt($value)
 */
	class Outlet extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property int|null $outlet_id
 * @property string $nama
 * @property string $kategori
 * @property string $ukuran
 * @property int $harga
 * @property int $modal
 * @property int $stok
 * @property string|null $foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Outlet|null $outlet
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereModal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereOutletId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUkuran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUserId($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property int|null $outlet_id
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Outlet|null $outlet
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOutletId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

