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

    private function checkCNS($cns): bool
    {
        if (strlen($cns) != 15) {
            return false;
        }

        $soma = 0;
        $multiplicador = 15;
        for ($i = 0; $i < 11; $i++) {
            $soma += (int) $cns[$i] * $multiplicador;
            $multiplicador--;
        }

        $resto = $soma % 11;
        $dv = 11 - $resto;
        if ($dv == 11) {
            $dv = 0;
        }

        if ($dv == 10) {
            $soma = 0;
            $multiplicador = 15;
            for ($i = 0; $i < 11; $i++) {
                $soma += (int) $cns[$i] * $multiplicador;
                $multiplicador--;
            }
            $soma += 2;
            $resto = $soma % 11;
            $dv = 11 - $resto;
            if ($dv == 11) {
                $dv = 0;
            }
            if ($dv == 10) {
                return false;
            }
        }

        $dvCNS = (int) substr($cns, 11, 2);
        if ($dvCNS != $dv) {
            return false;
        }

        return true;
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
