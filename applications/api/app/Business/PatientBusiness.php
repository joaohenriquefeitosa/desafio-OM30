<?php

namespace App\Business;

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
            $registers = Patient::search($search)
                ->orderBy($sortBy, $orderBy)
                ->paginate($length);

            return response()->json(['status' => true, 'data' => $registers]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error during access patient.'], status: Response::HTTP_NOT_FOUND);
        }
    }

    public function show($id): JsonResponse
    {
        $user = Patient::find($id);

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Patient not found.']);
        }

        return response()->json(['status' => true, 'data' => $user]);
    }

    public function store($data): JsonResponse
    {
        try {
            $register = Patient::create($data);
            return response()->json(['status' => true, 'message' => 'Patient created with success.', 'data' => $register]);
        } catch (\Throwable $th) {
            var_dump($th);
            return response()->json(['status' => false, 'message' => 'Error during create patient.'], status: Response::HTTP_NOT_FOUND);
        }
    }

    public function update($data, $id): JsonResponse
    {
        $position = Patient::find($id);
        if (!$position) {
            return response()->json(['status' => false, 'message' => 'Patient not found.']);
        }

        try {
            $register = Patient::find($position->id);
            $register->update($data);

            return response()->json(['status' => true, 'message' => 'Patient updated with success.']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error during update patient.'], status: Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy($id): JsonResponse
    {
        $register = Patient::find($id);

        if (!$register) {
            return response()->json(['status' => false, 'message' => 'Patient not found.']);
        }

        try {
            $register->delete();
            return response()->json(['status' => true, 'message' => 'Patient removed.']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error during remove patient.'], status: Response::HTTP_NOT_FOUND);
        }
    }
}
