<?php

namespace App\Http\Resources\OperationalSpesification;

use App\Enums\DateFormat;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationalSpesificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->_id,
            'time' => to_carbon($this->time)->format(DateFormat::HOUR_MINUTE),
            'specification' => $this->specification,
            'created_at' => $this->created_at->format(DateFormat::WITH_TIME),
            'updated_at' => $this->updated_at->format(DateFormat::WITH_TIME)
        ];
    }
}
