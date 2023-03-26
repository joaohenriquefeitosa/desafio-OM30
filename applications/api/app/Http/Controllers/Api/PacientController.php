<?php

namespace App\Http\Controllers\Api;

use App\Business\PacientBusiness;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pacient\IndexFormRequest;
use App\Http\Requests\Pacient\StoreFormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PacientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexFormRequest $request, PacientBusiness $pacientBusiness): JsonResponse
    {
        $data = $request->validated();
        return $pacientBusiness->index($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFormRequest $request, PacientBusiness $pacientBusiness): JsonResponse
    {
        $data = $request->validated();
        return $pacientBusiness->store($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, PacientBusiness $pacientBusiness)
    {
        $registers = $pacientBusiness->show($id);
        return $registers;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, PacientBusiness $pacientBusiness, string $id): JsonResponse
    {
        $data = $request->validated();
        return $pacientBusiness->update($data, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PacientBusiness $pacientBusiness, $id): JsonResponse
    {
        return $pacientBusiness->destroy($id);
    }
}
