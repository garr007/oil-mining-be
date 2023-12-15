<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OilTransfer extends Model
{
    use HasFactory;
    protected $table = 'oil_transfer';
    protected $fillable = [
        'Tanggal_Pindah', 'Lokasi_Pindah', 'Lokasi_Tujuan', 'Keterangan','oil_assets_id'
    ];
}
