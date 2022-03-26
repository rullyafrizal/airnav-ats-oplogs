<?php

namespace App\Http\Resources\Lecturer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LecturerCollection extends ResourceCollection
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
            'data' => LecturerResource::collection($this->collection),
        ];
    }
}
