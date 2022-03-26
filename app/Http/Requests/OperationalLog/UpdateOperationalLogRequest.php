<?php

namespace App\Http\Requests\OperationalLog;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOperationalLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => ['sometimes', 'required', 'date'],
            'session' => ['sometimes', 'required', 'string', 'in:PAGI,SIANG'],
            'time' => ['sometimes', 'required', 'date_format:H:i'],
            'facilities.tx_ht_twr' => ['sometimes', 'required', 'boolean'],
            'facilities.rx_ht_twr' => ['sometimes', 'required', 'boolean'],
            'facilities.tx_ht_pilot' => ['sometimes', 'required', 'boolean'],
            'facilities.rx_ht_pilot' => ['sometimes', 'required', 'boolean'],
            'facilities.weather_monitor' => ['sometimes', 'required', 'boolean'],
            'facilities.signal_lamp' => ['sometimes', 'required', 'boolean'],
            'facilities.papi' => ['sometimes', 'required', 'boolean'],
            'facilities.phone' => ['sometimes', 'required', 'boolean'],
            'cadet_on_duty' => ['sometimes', 'required', 'string'],
            'cadet_on_duty_signature' => [],
            'cadet_names' => ['sometimes', 'required', 'string'],
            'lecturer_names' => ['sometimes', 'required', 'string'],
            'operational_specifications' => ['sometimes', 'array'],
        ];
    }
}
