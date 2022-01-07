<?php

namespace App\Services\Api;

use App\Http\Resources\OperationalLog\OperationalLogResource;
use App\Models\OperationalLog;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OplogService
{
    public function store(array $payload = [])
    {
        $session = $this->startSession();
        $session->startTransaction();

        try {
            $oplog = OperationalLog::query()
                ->create([
                    'date' => $payload['date'],
                    'shift' => $payload['shift'],
                    'time' => $payload['time'],
                    'sign' => $payload['sign'],
                    'tx_122_4' => $payload['facilities']['tx_122_4'],
                    'rx_122_4' => $payload['facilities']['rx_122_4'],
                    'tx_120_55' => $payload['facilities']['tx_120_55'],
                    'rx_120_55' => $payload['facilities']['rx_120_55'],
                    'awos' => $payload['facilities']['awos'],
                    'signal_lamp' => $payload['facilities']['signal_lamp'],
                    'crash_bell' => $payload['facilities']['crash_bell'],
                    'sirine' => $payload['facilities']['sirine'],
                    'binocular' => $payload['facilities']['binocular'],
                    'vscs' => $payload['facilities']['vscs'],
                    'navaid_monitor' => $payload['facilities']['navaid_monitor'],
                    'fids' => $payload['facilities']['fids'],
                    'afls' => $payload['facilities']['afls'],
                    'aftn' => $payload['facilities']['aftn'],
                    'iais' => $payload['facilities']['iais'],
                    'ht_1' => $payload['facilities']['ht_1'],
                    'ht_2' => $payload['facilities']['ht_2'],
                    'ht_3' => $payload['facilities']['ht_3'],
                    'phone_coord' => $payload['facilities']['phone_coord'],
                    'phone_tele' => $payload['facilities']['phone_tele'],
                    'atc_on_duty' => $payload['atc_on_duty'],
                    'atc_on_duty_signature' => $payload['atc_on_duty_signature'],
                ]);

            $initials = array_filter(
                explode(',', trim($payload['controller_initial_names']))
            );

            if (count($initials)) {
                foreach ($initials as $initial) {
                    $oplog->controllerInitials()
                        ->create([
                            'name' => $initial
                        ]);
                }
            }

            if (count($payload['operational_specifications'])) {
                foreach ($payload['operational_specifications'] as $opspec) {
                    $oplog->operationalSpecifications()
                        ->create([
                            'time' => $opspec['time'],
                            'specification' => $opspec['specification'],
                        ]);
                }
            }

            $session->commitTransaction();
        } catch (\Exception $e) {
            $session->abortTransaction();
            return false;
        }
    }

    public function update(OperationalLog $operationalLog,array $payload = [])
    {
        $session = $this->startSession();
        $session->startTransaction();

        try {
            $diff = now()->diffInHours($operationalLog->created_at);

            if ($diff < 48) {
                $operationalLog->update([
                    'date' => $payload['date'],
                    'shift' => $payload['shift'],
                    'time' => $payload['time'],
                    'sign' => $payload['sign'],
                    'tx_122_4' => $payload['facilities']['tx_122_4'],
                    'rx_122_4' => $payload['facilities']['rx_122_4'],
                    'tx_120_55' => $payload['facilities']['tx_120_55'],
                    'rx_120_55' => $payload['facilities']['rx_120_55'],
                    'awos' => $payload['facilities']['awos'],
                    'signal_lamp' => $payload['facilities']['signal_lamp'],
                    'crash_bell' => $payload['facilities']['crash_bell'],
                    'sirine' => $payload['facilities']['sirine'],
                    'binocular' => $payload['facilities']['binocular'],
                    'vscs' => $payload['facilities']['vscs'],
                    'navaid_monitor' => $payload['facilities']['navaid_monitor'],
                    'fids' => $payload['facilities']['fids'],
                    'afls' => $payload['facilities']['afls'],
                    'aftn' => $payload['facilities']['aftn'],
                    'iais' => $payload['facilities']['iais'],
                    'ht_1' => $payload['facilities']['ht_1'],
                    'ht_2' => $payload['facilities']['ht_2'],
                    'ht_3' => $payload['facilities']['ht_3'],
                    'phone_coord' => $payload['facilities']['phone_coord'],
                    'phone_tele' => $payload['facilities']['phone_tele'],
                    'atc_on_duty' => $payload['atc_on_duty'],
                    'atc_on_duty_signature' => $payload['atc_on_duty_signature'],
                ]);

                $initials = array_filter(
                    explode(',', trim($payload['controller_initial_names']))
                );

                $operationalLog->controllerInitials()->delete();

                if (count($initials)) {
                    foreach ($initials as $initial) {
                        $operationalLog->controllerInitials()
                            ->create([
                                'name' => $initial
                            ]);
                    }
                }

                $operationalLog->operationalSpecifications()->delete();

                if (count($payload['operational_specifications'])) {
                    foreach ($payload['operational_specifications'] as $opspec) {
                        if (trim($opspec['specification']) && trim($opspec['time'])) {
                            $operationalLog->operationalSpecifications()
                                ->create([
                                    'time' => $opspec['time'],
                                    'specification' => $opspec['specification'],
                                ]);
                        } else {
                            continue;
                        }
                    }
                }

                $session->commitTransaction();

                return [
                    'status' => 'success',
                    'message' => 'Operational Log has been updated'
                ];
            }


            $session->commitTransaction();

            return [
                'status' => 'error',
                'message' => 'Operational Log can\'t be updated, due to more than 48 hours no edit policy'
            ];
        } catch (\Exception $e) {
            $session->abortTransaction();
            return false;
        }
    }

    public function destroy(OperationalLog $operationalLog) {
        $session = $this->startSession();
        $session->startTransaction();

        try {
            $operationalLog->controllerInitials()->delete();

            $operationalLog->operationalSpecifications()->delete();

            $operationalLog->delete();

            $session->commitTransaction();

            return [
                'status' => 'success',
                'message' => 'Operational Log has been deleted'
            ];
        } catch (\Exception $e) {
            $session->abortTransaction();
            return false;
        }
    }

    public function export(OperationalLog $operationalLog)
    {
        $pdf = PDF::loadView('downloads.operational_log.pdf', [
            'operationalLog' => (new OperationalLogResource($operationalLog))->response()->getData()
        ]);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('operational_log.pdf');
    }

    protected function startSession()
    {
        return DB::connection('mongodb')->getMongoClient()->startSession();
    }
}
