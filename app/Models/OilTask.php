<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OilTask extends Model
{
    use HasFactory;
    protected $table = 'oil_task';
    protected $fillable = [
        'Judul', 'Isi', 'employee_id'
    ];


    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeStatus::class, 'employee_id', 'id');
    }
}
