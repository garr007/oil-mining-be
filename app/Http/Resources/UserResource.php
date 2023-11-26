<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            'uuid' => $this->uuid,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "email" => $this->email,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "religion" => $this->religion,
            "phone" => $this->phone,
            "address" => $this->address,
            "birth_date" => $this->birth_date,
            "social_number" => $this->social_number,
            "img" => User::getImgLink($this->img),
            "employee" => new EmployeeResource($this->whenLoaded("employee")),
        ];
    }
}
