<?php

namespace Modules\Products\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $data = $this->resource->toArray();
        $data['first_page_url'] = $data['path'];
        $data['per_page'] = 1;

        return $data;
    }
}
