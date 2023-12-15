<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OilAssetsResource extends JsonResource
{
    /** 
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'Nama Aset' => $this->Nama_Aset,
            'Jenis Aset' => $this->Jenis_Aset,
            'Status Aset' => $this->Status_Aset,
            'Riwayat Status' => $this->Riwayat_Status,

        ];
    }
}
