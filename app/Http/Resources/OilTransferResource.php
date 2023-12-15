<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OilTransferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array

    {
        $tanggalPindah = new \DateTime($this->Tanggal_Pindah);
        return [
            'id' => $this->id,
            'id Asset' => $this->oil_assets_id,
            'Tanggal Pindah' => $tanggalPindah->format("d/m/Y"),
            'Lokasi Pindah' => $this->Lokasi_Pindah,
            'Lokasi Tujuan' => $this->Lokasi_Tujuan,
            'Keterangan' => $this->Keterangan,
        ];
    }
}
