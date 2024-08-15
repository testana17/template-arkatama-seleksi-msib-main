<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Carbon\Carbon;

class Travel extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'travel';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    protected $fillable = [
        'nama',
        'tanggal_keberangkatan',
        'kuota',
        'sisa_kuota',
    ];

    // Relasi ke tabel Penumpang
    public function penumpang()
    {
        return $this->hasMany(Penumpang::class, 'id_travel');
    }
    public function getTanggalKeberangkatanAttribute($value)
    {
        return Carbon::parse($value);
    }
}
