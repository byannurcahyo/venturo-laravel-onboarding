<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'product_category_id' => $this->m_product_category_id,
            'product_category_name' => isset($this->category) ? $this->category->name : "",
            'is_available' => $this->is_available,
            'description' => $this->description,
            'photo_url' => $this->photo ? url('storage/'.$this->photo) : null,
            'details' => ProductDetailResource::collection($this->details),
        ];
    }
}
