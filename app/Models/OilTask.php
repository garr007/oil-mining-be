<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OilTask extends Model
{
    use HasFactory;
    protected $table = 'oil_task';
    protected $fillable = [
        'Judul', 'Isi'
    ];
}
