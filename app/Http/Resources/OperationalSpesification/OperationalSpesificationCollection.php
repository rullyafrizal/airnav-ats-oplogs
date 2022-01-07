<?php

namespace App\Http\Resources\OperationalSpesification;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OperationalSpesificationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => OperationalSpesificationResource::collection($this->collection)
        ];
    }
}
