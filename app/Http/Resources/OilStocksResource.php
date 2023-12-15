<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OilStocksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    public function toArray($request): array
    {
        // Ubah nilai Tanggal_Masuk dan Tanggal_Keluar menjadi objek DateTime
        $tanggalMasuk = new \DateTime($this->Tanggal_Masuk);
        $tanggalKeluar = new \DateTime($this->Tanggal_Keluar);

        return [
            'Jenis Minyak' => $this->Jenis_Minyak,
            'Jumlah' => $this->Jumlah,
            'Tanggal Masuk' => $tanggalMasuk->format("d/m/Y"),
            'Tanggal Keluar' => $tanggalKeluar->format("d/m/Y"),
            'Lokasi Penyimpanan' => $this->Lokasi_Penyimpanan,
            'Keterangan' => $this->Keterangan,
        ];
    }
}
