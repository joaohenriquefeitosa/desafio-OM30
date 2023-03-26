<?php

namespace App\Business;

use App\Models\Address;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PatientBusiness
{
    public function index($data): JsonResponse
    {
        $length = isset($data['length']) ? $data['length'] : null;
        $sortBy = isset($data['sort_by']) ? $data['sort_by'] : 'id';
        $orderBy = isset($data['order_by']) ? $data['order_by'] : 'desc';
        $search = isset($data['search']) ? $data['search'] : null;

        try {
            $registers = Patient::with('address')
                ->search($search)
                ->orderBy($sortBy, $orderBy)
                ->paginate($length);

            return response()->json(['status' => true, 'data' => $registers]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error during access patient.'], status: Response::HTTP_NOT_FOUND);
        }
    }

    public function show($id): JsonResponse
    {
        $patient = Patient::with('address')->find($id);
        if (!$patient) {
            return response()->json(['status' => false, 'message' => 'Patient not found.'], status: Response::HTTP_NOT_FOUND);
        }

        return response()->json(['status' => true, 'data' => $patient]);
    }

    public function store($data): JsonResponse
    {
        if (!$this->checkCNS($data['cns'])) {
            return response()->json(['status' => false, 'message' => 'CNS invalid.']);
        }

        if (!$this->checkCPF($data['cpf'])) {
            return response()->json(['status' => false, 'message' => 'CPF invalid.']);
        }

        try {
            $patient = new Patient($data);

            if (isset($data['picture'])) {
                $uniqueId = uniqid();
                $extension = $data['picture']->extension();
                $filename = "{$uniqueId}.{$extension}";
                $path = $data['picture']->storeAs('public/patients', $filename);
                $patient->picture = $path;
            }

            $patient->save();

            $address = new Address($data);
            $address->patient()->associate($patient);
            $address->save();

            $patient = $patient->refresh()->load('address');

            return response()->json(['status' => true, 'message' => 'Patient created with success.', 'data' => $patient]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error during create patient.'], status: Response::HTTP_NOT_FOUND);
        }
    }

    public function update($data, $id): JsonResponse
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['status' => false, 'message' => 'Patient not found.'], status: Response::HTTP_NOT_FOUND);
        }

        if (isset($data['cns']) && !$this->checkCNS($data['cns'])) {
            return response()->json(['status' => false, 'message' => 'CNS invalid.']);
        }

        if (isset($data['cpf']) && !$this->checkCPF($data['cpf'])) {
            return response()->json(['status' => false, 'message' => 'CPF invalid.']);
        }



        try {
            if (isset($data['picture'])) {
                Storage::disk('public')->delete($patient->picture);
                $patient->picture = $data['picture'];
            }

            $patient->update($data);

            $address = $patient->address;
            $address->fill($data);
            $address->patient()->associate($patient);
            $address->save();

            // Retrieve the patient data with the associated address
            $patient = $patient->refresh()->load('address');

            return response()->json(['status' => true, 'message' => 'Patient updated with success.', 'data' => $patient]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error during update patient.'], status: Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy($id): JsonResponse
    {
        $patient = Patient::with('address')->find($id);
        if (!$patient) {
            return response()->json(['status' => false, 'message' => 'Patient not found.'], status: Response::HTTP_NOT_FOUND);
        }

        try {
            $patient->delete();
            return response()->json(['status' => true, 'message' => 'Patient removed.']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error during remove patient.'], status: Response::HTTP_NOT_FOUND);
        }
    }

    public function checkCNS($cns)
    {
        $cns = preg_replace('/[^0-9]/', '', (string) $cns);

        if (strlen($cns) != 15) {
            return false;
        }

        $invalid = [
            '000000000000000',
            '111111111111111',
            '222222222222222',
            '333333333333333',
            '444444444444444',
            '555555555555555',
            '666666666666666',
            '777777777777777',
            '888888888888888',
            '999999999999999'
        ];

        if (in_array($cns, $invalid)) {
            return false;
        }

        $action = substr($cns, 0, 1);

        switch ($action):
            case '1':
            case '2':
                $ret = $this->validaCns($cns);
                break;
            case '7':
                $ret = $this->validaCnsProvisorio($cns);
                break;
            case '8':
                $ret = $this->validaCnsProvisorio($cns);
                break;
            case '9':
                $ret = $this->validaCnsProvisorio($cns);
                break;
            default:
                $ret = false;
        endswitch;

        return $ret;
    }

    public function validaCns($cns)
    {
        $pis = substr($cns, 0, 11);
        $value = 0;

        for ($i = 0, $j = strlen($pis), $k = 15; $i < $j; $i++, $k--) :
            $value += $pis[$i] * $k;
        endfor;

        $dv = 11 - fmod($value, 11);
        $dv = ($dv != 11) ? $dv : '0';

        if ($dv == 10) {
            $value += 2;
            $dv = 11 - fmod($value, 11);
            $response = $pis . '001' . $dv;
        } else {
            $response = $pis . '000' . $dv;
        }

        if ($cns != $response) {
            return false;
        } else {
            return true;
        }
    }

    public function validaCnsProvisorio($cns)
    {
        $value = 0;

        for ($i = 0, $j = strlen($cns), $k = $j; $i < $j; $i++, $k--) :
            $value += $cns[$i] * $k;
        endfor;

        return $value % 11 == 0 && $j == 15;
    }


    private function checkCPF($cpf): bool
    {
        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += (int) $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        if ($resto < 2) {
            $dv1 = 0;
        } else {
            $dv1 = 11 - $resto;
        }
        if ($dv1 != (int) $cpf[9]) {
            return false;
        }

        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += (int) $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        if ($resto < 2) {
            $dv2 = 0;
        } else {
            $dv2 = 11 - $resto;
        }
        if ($dv2 != (int) $cpf[10]) {
            return false;
        }

        return true;
    }
}
