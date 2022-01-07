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
            'shift' => ['sometimes', 'required', 'string', 'in:PAGI,SIANG'],
            'time' => ['sometimes', 'required', 'date_format:H:i'],
            'sign' => ['sometimes', 'required', 'boolean'],
            'facilities.tx_122_4' => ['sometimes', 'required', 'boolean'],
            'facilities.rx_122_4' => ['sometimes', 'required', 'boolean'],
            'facilities.tx_120_55' => ['sometimes', 'required', 'boolean'],
            'facilities.rx_120_55' => ['sometimes', 'required', 'boolean'],
            'facilities.awos' => ['sometimes', 'required', 'boolean'],
            'facilities.signal_lamp' => ['sometimes', 'required', 'boolean'],
            'facilities.crash_bell' => ['sometimes', 'required', 'boolean'],
            'facilities.sirine' => ['sometimes', 'required', 'boolean'],
            'facilities.binocular' => ['sometimes', 'required', 'boolean'],
            'facilities.vscs' => ['sometimes', 'required', 'boolean'],
            'facilities.navaid_monitor' => ['sometimes', 'required', 'boolean'],
            'facilities.fids' => ['sometimes', 'required', 'boolean'],
            'facilities.afls' => ['sometimes', 'required', 'boolean'],
            'facilities.aftn' => ['sometimes', 'required', 'boolean'],
            'facilities.iais' => ['sometimes', 'required', 'boolean'],
            'facilities.ht_1' => ['sometimes', 'required', 'boolean'],
            'facilities.ht_2' => ['sometimes', 'required', 'boolean'],
            'facilities.ht_3' => ['sometimes', 'required', 'boolean'],
            'facilities.phone_coord' => ['sometimes', 'required', 'boolean'],
            'facilities.phone_tele' => ['sometimes', 'required', 'boolean'],
            'atc_on_duty' => ['sometimes', 'required', 'string'],
            'atc_on_duty_signature' => [],
            'controller_initial_names' => ['sometimes', 'required', 'string'],
            'operational_specifications' => ['sometimes', 'required', 'array'],
        ];
    }
}
