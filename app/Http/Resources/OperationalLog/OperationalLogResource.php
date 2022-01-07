<?php

namespace App\Http\Resources\OperationalLog;

use App\Enums\DateFormat;
use App\Http\Resources\ControllerInitial\ControllerInitialCollection;
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
            'controller_initials' => new ControllerInitialCollection($this->controllerInitials),
            'operational_specifications' => new OperationalSpesificationCollection($this->operationalSpecifications),
            'date' => to_carbon($this->date)->format(DateFormat::ONLY_DATE_2),
            'date_2' => to_carbon($this->date)->format(DateFormat::ONLY_DATE),
            'shift' => $this->shift,
            'atc_on_duty' => $this->atc_on_duty,
            'atc_on_duty_signature' => $this->atc_on_duty_signature,
            'time' => to_carbon($this->time)->format(DateFormat::HOUR_MINUTE),
            'sign' => $this->sign,
            'tx_122_4' => $this->tx_122_4,
            'rx_122_4' => $this->rx_122_4,
            'tx_120_55' => $this->tx_120_55,
            'rx_120_55' => $this->rx_120_55,
            'awos' => $this->awos,
            'signal_lamp' => $this->signal_lamp,
            'crash_bell' => $this->crash_bell,
            'sirine' => $this->sirine,
            'binocular' => $this->binocular,
            'vscs' => $this->vscs,
            'navaid_monitor' => $this->navaid_monitor,
            'fids' => $this->fids,
            'afls' => $this->afls,
            'aftn' => $this->aftn,
            'iais' => $this->iais,
            'ht_1' => $this->ht_1,
            'ht_2' => $this->ht_2,
            'ht_3' => $this->ht_3,
            'phone_coord' => $this->phone_coord,
            'phone_tele' => $this->phone_tele,
            'created_at' => $this->created_at->format(DateFormat::WITH_TIME),
            'updated_at' => $this->updated_at->format(DateFormat::WITH_TIME),
            'is_updatable' => now()->diffInHours($this->created_at) < 48 ? true : false
        ];
    }
}
