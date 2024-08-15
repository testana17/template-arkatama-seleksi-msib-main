<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pemesanan extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'pemesanan';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_penumpang',
        'id_travel',
        'status',
        'tanggal_pemesanan',
    ];

    // Relasi ke tabel Penumpang
    public function penumpang()
    {
        return $this->belongsTo(Penumpang::class, 'id_penumpang');
    }

    // Relasi ke tabel Travel
    public function travel()
    {
        return $this->belongsTo(Travel::class, 'id_travel');
    }
}
