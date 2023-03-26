<?php

namespace App\Http\Controllers\Api;

use App\Business\PatientBusiness;
use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\IndexFormRequest;
use App\Http\Requests\Patient\StoreFormRequest;
use App\Http\Requests\Patient\UpdateFormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexFormRequest $request, PatientBusiness $patientBusiness): JsonResponse
    {
        $data = $request->validated();
        return $patientBusiness->index($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFormRequest $request, PatientBusiness $patientBusiness): JsonResponse
    {
        $data = $request->validated();
        return $patientBusiness->store($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, PatientBusiness $patientBusiness)
    {
        $registers = $patientBusiness->show($id);
        return $registers;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, PatientBusiness $patientBusiness, string $id): JsonResponse
    {
        $data = $request->validated();
        return $patientBusiness->update($data, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatientBusiness $patientBusiness, $id): JsonResponse
    {
        return $patientBusiness->destroy($id);
    }
}
