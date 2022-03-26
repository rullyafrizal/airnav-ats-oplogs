<?php

namespace App\Http\Requests\OperationalLog;

use Illuminate\Foundation\Http\FormRequest;

class CreateOperationalLogRequest extends FormRequest
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
            'date' => ['required', 'date'],
            'session' => ['required', 'string', 'in:PAGI,SIANG'],
            'time' => ['required', 'date_format:H:i'],
            'facilities.tx_ht_twr' => ['required', 'boolean'],
            'facilities.rx_ht_twr' => ['required', 'boolean'],
            'facilities.tx_ht_pilot' => ['required', 'boolean'],
            'facilities.rx_ht_pilot' => ['required', 'boolean'],
            'facilities.weather_monitor' => ['required', 'boolean'],
            'facilities.signal_lamp' => ['required', 'boolean'],
            'facilities.papi' => ['required', 'boolean'],
            'facilities.phone' => ['required', 'boolean'],
            'cadet_on_duty' => ['required', 'string'],
            'cadet_on_duty_signature' => [],
            'cadet_names' => ['required', 'string'],
            'lecturer_names' => ['required', 'string'],
            'operational_specifications' => ['array'],
        ];
    }
}
