<?php

namespace App\Business;

use App\Models\Pacient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PacientBusiness
{
    public function index($data): JsonResponse
    {
        $length = isset($data['length']) ? $data['length'] : null;
        $sortBy = isset($data['sort_by']) ? $data['sort_by'] : 'id';
        $orderBy = isset($data['order_by']) ? $data['order_by'] : 'desc';
        $search = isset($data['search']) ? $data['search'] : null;

        try {
            $registers = Pacient::search($search)
                ->orderBy($sortBy, $orderBy)
                ->paginate($length);

            return response()->json(['status' => true, 'data' => $registers]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error during access matter.'], status: Response::HTTP_NOT_FOUND);
        }
    }

    public function show($id): JsonResponse
    {
        $user = Pacient::find($id);

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Pacient not found.']);
        }

        return response()->json(['status' => true, 'data' => $user]);
    }

    public function store($data): JsonResponse
    {
        try {
            $register = Pacient::create($data);
            return response()->json(['status' => true, 'message' => 'Pacient created with success.', 'data' => $register]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error during create pacient.'], status: Response::HTTP_NOT_FOUND);
        }
    }

    public function update($data, $id): JsonResponse
    {
        $position = Pacient::find($id);
        if (!$position) {
            return response()->json(['status' => false, 'message' => 'Pacient not found.']);
        }

        try {
            $register = Pacient::find($position->id);
            $register->update($data);

            return response()->json(['status' => true, 'message' => 'Pacient updated with success.']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error during update pacient.'], status: Response::HTTP_NOT_FOUND);
        }
    }

    public function delete($id): JsonResponse
    {
        $register = Pacient::find($id);

        if (!$register) {
            return response()->json(['status' => false, 'message' => 'Pacient not found.']);
        }

        try {
            $register->delete();
            return response()->json(['status' => true, 'message' => 'Pacient removed.']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error during remove pacient.'], status: Response::HTTP_NOT_FOUND);
        }
    }
}
