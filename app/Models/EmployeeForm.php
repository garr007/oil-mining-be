<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeForm extends Model
{
    use HasFactory;
    protected $table = 'employee_form';

    protected $fillable = [
        'Riwayat_Penyakit_Jantung', 'Gejala_Penyakit_Jantung', 'Penurunan_Kinerja_Fisik', 'Gangguan_Pendengaran',
        'Gejala_Gangguan_Pendengaran', 'Pelindung_Pendengaran', 'Kecelakaan_Keamanan', 'Jelaskan_Ancaman_Keamanan',
        'Aktivitas_Pekerjaan', 'Laporan_Tambahan', 'personal_id'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(PersonalData::class, 'personal_id', 'id');
    }
}
