<?php

namespace App\Http\Resources\User\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuProductsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'price' => $this->price
        ];
    }
}
