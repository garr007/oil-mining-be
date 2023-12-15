<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OilStocks extends Model
{
    use HasFactory;
    protected $table = 'oil_stocks';

    protected $fillable = [
        'Jenis_Minyak', 'Jumlah', 'Tanggal_Masuk', 'Tanggal_Keluar',
        'Lokasi_Penyimpanan', 'Keterangan', 'oil_assets_id'
    ];

    /**
     * Get the user that owns the OilStocks
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
}
