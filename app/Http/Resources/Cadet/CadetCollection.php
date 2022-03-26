<?php

namespace App\Http\Resources\Cadet;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CadetCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => CadetResource::collection($this->collection),
        ];
    }
}
