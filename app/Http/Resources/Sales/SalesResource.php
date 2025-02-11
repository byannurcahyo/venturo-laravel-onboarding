<?php

namespace App\Http\Resources\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesResource extends JsonResource
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
            'm_customer_id' => $this->m_customer_id,
            'm_customer_name' => $this->customer->name,
            'date' => $this->date,
            'details' => SalesDetailResource::collection($this->details),
        ];
    }
}
