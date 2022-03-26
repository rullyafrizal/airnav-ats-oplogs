<?php

namespace App\Services\Api;

use App\Http\Resources\OperationalLog\OperationalLogResource;
use App\Models\OperationalLog;
use PDF;
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
                    'session' => $payload['session'],
                    'time' => $payload['time'],
                    'tx_ht_twr' => $payload['facilities']['tx_ht_twr'],
                    'rx_ht_twr' => $payload['facilities']['rx_ht_twr'],
                    'tx_ht_pilot' => $payload['facilities']['tx_ht_pilot'],
                    'rx_ht_pilot' => $payload['facilities']['rx_ht_pilot'],
                    'weather_monitor' => $payload['facilities']['weather_monitor'],
                    'signal_lamp' => $payload['facilities']['signal_lamp'],
                    'papi' => $payload['facilities']['papi'],
                    'phone' => $payload['facilities']['phone'],
                    'cadet_on_duty' => $payload['cadet_on_duty'],
                    'cadet_on_duty_signature' => $payload['cadet_on_duty_signature'],
                ]);

            $cadets = array_filter(
                explode(',', trim($payload['cadet_names']))
            );

            if (count($cadets)) {
                foreach ($cadets as $cadet) {
                    $oplog->cadets()
                        ->create([
                            'name' => $cadet
                        ]);
                }
            }

            $lecturers = array_filter(
                explode(',', trim($payload['lecturer_names']))
            );

            if (count($lecturers)) {
                foreach ($lecturers as $lecturer) {
                    $oplog->lecturers()
                        ->create([
                            'name' => $lecturer
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
                    'session' => $payload['session'],
                    'time' => $payload['time'],
                    'tx_ht_twr' => $payload['facilities']['tx_ht_twr'],
                    'rx_ht_twr' => $payload['facilities']['rx_ht_twr'],
                    'tx_ht_pilot' => $payload['facilities']['tx_ht_pilot'],
                    'rx_ht_pilot' => $payload['facilities']['rx_ht_pilot'],
                    'weather_monitor' => $payload['facilities']['weather_monitor'],
                    'signal_lamp' => $payload['facilities']['signal_lamp'],
                    'papi' => $payload['facilities']['papi'],
                    'phone' => $payload['facilities']['phone'],
                    'cadet_on_duty' => $payload['cadet_on_duty'],
                    'cadet_on_duty_signature' => $payload['cadet_on_duty_signature'],
                ]);

                $initials = array_filter(
                    explode(',', trim($payload['cadet_names']))
                );

                $operationalLog->cadets()->delete();

                if (count($initials)) {
                    foreach ($initials as $initial) {
                        $operationalLog->cadets()
                            ->create([
                                'name' => $initial
                            ]);
                    }
                }

                $lecturers = array_filter(
                    explode(',', trim($payload['lecturer_names']))
                );

                $operationalLog->lecturers()->delete();

                if (count($lecturers)) {
                    foreach ($lecturers as $lecturer) {
                        $operationalLog->lecturers()
                            ->create([
                                'name' => $lecturer
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
            $operationalLog->cadets()->delete();

            $operationalLog->lecturers()->delete();

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
        $pdf = PDF::loadView('downloads.operational_log.pdf2', [
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
