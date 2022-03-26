<?php

namespace App\Http\Resources\Cadet;

use App\Enums\DateFormat;
use Illuminate\Http\Resources\Json\JsonResource;

class CadetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->_id,
            'name' => $this->name,
            'created_at' => $this->created_at->format(DateFormat::WITH_TIME),
            'updated_at' => $this->updated_at->format(DateFormat::WITH_TIME)
        ];
    }
}
