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
            'shift' => ['required', 'string', 'in:PAGI,SIANG'],
            'time' => ['required', 'date_format:H:i'],
            'sign' => ['required', 'boolean'],
            'facilities.tx_122_4' => ['required', 'boolean'],
            'facilities.rx_122_4' => ['required', 'boolean'],
            'facilities.tx_120_55' => ['required', 'boolean'],
            'facilities.rx_120_55' => ['required', 'boolean'],
            'facilities.awos' => ['required', 'boolean'],
            'facilities.signal_lamp' => ['required', 'boolean'],
            'facilities.crash_bell' => ['required', 'boolean'],
            'facilities.sirine' => ['required', 'boolean'],
            'facilities.binocular' => ['required', 'boolean'],
            'facilities.vscs' => ['required', 'boolean'],
            'facilities.navaid_monitor' => ['required', 'boolean'],
            'facilities.fids' => ['required', 'boolean'],
            'facilities.afls' => ['required', 'boolean'],
            'facilities.aftn' => ['required', 'boolean'],
            'facilities.iais' => ['required', 'boolean'],
            'facilities.ht_1' => ['required', 'boolean'],
            'facilities.ht_2' => ['required', 'boolean'],
            'facilities.ht_3' => ['required', 'boolean'],
            'facilities.phone_coord' => ['required', 'boolean'],
            'facilities.phone_tele' => ['required', 'boolean'],
            'atc_on_duty' => ['required', 'string'],
            'atc_on_duty_signature' => [],
            'controller_initial_names' => ['required', 'string'],
            'operational_specifications' => ['array'],
        ];
    }
}
