<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OilAssets extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'oil_assets';

    protected $fillable = [
        'Nama_Aset', 'Jenis_Aset', 'Status_Aset', 'Riwayat_Status','Keterangan','id'
    ];
}
