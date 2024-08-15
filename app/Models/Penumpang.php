<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Penumpang extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'penumpangs';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_travel',
        'kode_booking',
        'nama',
        'jenis_kelamin',
        'kota',
        'usia',
        'tahun_lahir',
    ];

    // Relasi ke tabel Travel
    public function travel()
    {
        return $this->belongsTo(Travel::class, 'id_travel');
    }
}
