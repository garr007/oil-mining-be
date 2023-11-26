<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "user_id" => $this->user_id,
            "position_id" => $this->position_id,
            "user" => new UserResource($this->whenLoaded("user")),
            "status" => new EmployeeStatusResource($this->whenLoaded("status")),
            "position" => new PositionResource($this->whenLoaded("position")),
        ];
    }
}
