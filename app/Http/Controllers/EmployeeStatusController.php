<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeStatusRequest;
use App\Http\Resources\EmployeeStatusResource;
use App\Models\EmployeeStatus;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->response(
            EmployeeStatusResource::collection(EmployeeStatus::all()),
            "found",
            Response::HTTP_OK,
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeStatusRequest $employeeStatus)
    {
        $data = $employeeStatus->validated();

        $employeeStatus = EmployeeStatus::create([$data]);

        $this->response(
            new EmployeeStatusResource($employeeStatus),
            "created",
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource (by id).
     */
    public function show(Request $request, $id)
    {
        return $this->response(
            new EmployeeStatusResource(EmployeeStatus::findOrFail($id)),
            "found",
            Response::HTTP_OK,
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeStatus $employeeStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeStatus $employeeStatus)
    {
        //
    }
}
