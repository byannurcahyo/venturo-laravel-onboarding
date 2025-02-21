<?php

namespace App\Http\Resources\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesDetailResource extends JsonResource
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
            't_sales_id' => $this->t_sales_id,
            'm_product_id' => $this->m_product_id,
            'm_product_detail_id' => $this->m_product_detail_id,
            'total_item' => $this->total_item,
            'price' => $this->price,
            'product' => $this->product->name,
            'detail_product' => $this->productDetail->description,
        ];
    }
}
