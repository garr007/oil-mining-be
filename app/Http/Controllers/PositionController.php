<?php

namespace App\Http\Controllers;

use App\Http\Resources\PositionResource;
use App\Models\Position;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //'page' handled by laravel
        $perPage = $request->input('per_page', 20); //get
        $perPage = $perPage > 20 ? 20 : $perPage; //set max

        $positions = Position::with('division')->paginate($perPage);
        // $positions = Position::all();

        $positions->data = PositionResource::collection($positions);

        if ($positions->data->isEmpty()) {
            return $this->response($positions, "not found", Response::HTTP_NOT_FOUND);
        }
        return $this->response($positions, "success", Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePositionRequest $request)
    {
        $data = collect($request->validated());

        try {
            Position::create($data);
        } catch (\Exception $e) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->errResponse(Response::$statusTexts[$code], $code);
        }

        // FIXME !!! RETURN RESOURCE
        return $this->response(null, "", Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $position)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePositionRequest $request, Position $position)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        //
    }
}
