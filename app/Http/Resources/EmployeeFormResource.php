<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Riwayat_Kesehatan_Jantung' => $this->Gejala_Penyakit_Jantung,
            'Penurunan_Kinerja_Fisik' => $this->Penurunan_Kinerja_Fisik,
            'Ancaman_Keamanan' => $this->Jelaskan_Ancaman_Keamanan,
            'Laporan_Tambahan' => $this->Laporan_Tambahan,
            'employee' => $this->employee
        ];
    }
}
