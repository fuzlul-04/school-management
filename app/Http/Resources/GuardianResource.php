<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuardianResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'bangla_name' => $this->bangla_name,
            'relation' => $this->relation,
            'phone' => $this->phone,
            'alternative_phone' => $this->alternative_phone,
            'email' => $this->email,
            'nid_number' => $this->nid_number,
            'profession' => $this->profession,
            'address' => $this->address,
            'profile_image' => $this->profile_image ? asset('storage/' . $this->profile_image) : null,
            'status' => $this->status,
            'is_primary' => $this->pivot?->is_primary ?? false,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
