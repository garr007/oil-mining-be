<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Lokasi' => $this->Lokasi_Pekerjaan,
            'Kontak_Darurat' => $this->Kontak_Darurat,
            'employee' => $this->employee
        ];
    }
}
