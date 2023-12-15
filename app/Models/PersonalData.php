<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonalData extends Model
{
    use HasFactory;
    protected $table = 'personal_data';
    protected $fillable = [
        'employee_id', 'Lokasi_Pekerjaan', 'Kontak_Darurat'
    ];


    /**
     * Get the user that owns the PersonalData
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeStatus::class, 'employee_id', 'id');
    }
}
