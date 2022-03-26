<?php

namespace App\Http\Resources\OperationalLog;

use App\Enums\DateFormat;
use App\Http\Resources\Cadet\CadetCollection;
use App\Http\Resources\ControllerInitial\ControllerInitialCollection;
use App\Http\Resources\Lecturer\LecturerCollection;
use App\Http\Resources\OperationalSpesification\OperationalSpesificationCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationalLogResource extends JsonResource
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
            'cadets' => new CadetCollection($this->cadets),
            'lecturers' => new LecturerCollection($this->lecturers),
            'operational_specifications' => new OperationalSpesificationCollection($this->operationalSpecifications),
            'date' => to_carbon($this->date)->format(DateFormat::ONLY_DATE_2),
            'date_2' => to_carbon($this->date)->format(DateFormat::ONLY_DATE),
            'session' => $this->session,
            'cadet_on_duty' => $this->cadet_on_duty,
            'cadet_on_duty_signature' => $this->cadet_on_duty_signature,
            'time' => to_carbon($this->time)->format(DateFormat::HOUR_MINUTE),
            'tx_ht_twr' => $this->tx_ht_twr,
            'rx_ht_twr' => $this->rx_ht_twr,
            'tx_ht_pilot' => $this->tx_ht_pilot,
            'rx_ht_pilot' => $this->rx_ht_pilot,
            'weather_monitor' => $this->weather_monitor,
            'signal_lamp' => $this->signal_lamp,
            'papi' => $this->papi,
            'phone' => $this->phone,
            'created_at' => $this->created_at->format(DateFormat::WITH_TIME),
            'updated_at' => $this->updated_at->format(DateFormat::WITH_TIME),
            'is_updatable' => now()->diffInHours($this->created_at) < 48 ? true : false
        ];
    }
}
