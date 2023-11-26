<?php

namespace App\Http\Controllers;

use App\Http\Resources\DivisionResource;
use App\Http\Resources\UserResource;
use App\Models\Division;
use App\Http\Requests\StoreDivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;
use App\Models\User;
use DB;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DivisionController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
    //  */
    public function __construct()
    {
        // $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //'page' handled by laravel
        $perPage = $request->input('per_page', 20); //get
        $perPage = $perPage > 20 ? 20 : $perPage; //set max

        $withPosition = $request->input('with_position', 'false');

        Log::debug('searching for divisions with pagination');
        $divisions = strcasecmp($withPosition, 'true') == 0 ?
            Division::with('positions')->paginate($perPage) :
            Division::paginate($perPage);


        $divisions->data = DivisionResource::collection($divisions->items());

        if (!$divisions->data->isEmpty()) {
            return $this->response($divisions, "not found", Response::HTTP_NOT_FOUND);
        }

        return $this->response($divisions, "found", Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDivisionRequest $request)
    {
        $data = $request->validated();

        try {
            $division = Division::create($data);
        } catch (\Exception $e) {
            return $this->errResponse($request, "DivisionController store, db failure when creating division");
        }

        return $this->response(
            new DivisionResource($division),
            "division created",
            Response::HTTP_CREATED
        );
    }

    /**
     * Get resource by id.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $withPosition = $request->input('with_position', 'false');

        Log::debug('searching for division by id');
        $division = strcasecmp($withPosition, 'true') == 0 ?
            Division::with('positions')->findOrFail($id) :
            Division::findOrFail($id);

        return $this->response(
            new DivisionResource($division),
            'found',
            Response::HTTP_OK
        );
    }

    /**
     * Get resource by id (with employees)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function showWithEmployees(Request $request, $id)
    {
        //'page' handled by laravel
        $perPage = $request->input('per_page', 20); //get
        $perPage = $perPage > 20 ? 20 : $perPage; //set max

        $users = User::with('employee.position.division', 'employee.status')->where(function (Builder $query) {
            $query->select('divisions.id')
                ->from('divisions')
                ->join('positions', 'positions.division_id', '=', 'divisions.id')
                ->join('employees', 'employees.position_id', '=', 'positions.id')
                ->whereColumn('employees.user_id', 'users.id')
                ->limit(1);
        }, $id)->paginate($perPage);

        $users->data = UserResource::collection($users);

        if ($users->data->isEmpty()) {
            return $this->response($users, 'not found', Response::HTTP_NOT_FOUND);
        }

        return $this->response($users, 'found', Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDivisionRequest $request)
    {
        // TODO
        return $this->errResponse($request, "not implemented", Response::HTTP_NOT_IMPLEMENTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // TODO
        return $this->errResponse($request, "not implemented", Response::HTTP_NOT_IMPLEMENTED);
    }
}
