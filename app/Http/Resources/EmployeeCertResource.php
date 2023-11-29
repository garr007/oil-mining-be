<?php

namespace App\Http\Resources;

use App\Models\EmployeeCert;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeCertResource extends JsonResource
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
            'uuid' => $this->uuid,
            'employee_id' => $this->employee_id,
            'code' => $this->code,
            'date' => $this->date,
            'exp_date' => $this->exp_date,
            'type' => $this->type,
            'cert' => EmployeeCert::getDownloadLink($this->cert),
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
        ];
    }
}
