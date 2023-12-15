<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterEmployeeRequest;
use App\Http\Requests\ShowEmployeeRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Division;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Log;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api');
    }


    /**
     * Display a listing of the resource.
     * with pagination & is_active filter
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 20); //get
        $perPage = $perPage > 20 ? 20 : $perPage; //set max

        $is_active = $request->input('is_active', 'true');

        Log::debug('searching for employee with pagination');

        $users = User::with('employee.position.division', 'employee.status');
        $users = strcmp($is_active, 'false') == 0 ?
            $users->has('employee') :
            $users->whereHas(
                'employee',
                function ($query) {
                    return $query->where('is_active', '=', true);
                }
            );
        $users = $users->paginate($perPage);

        $users->data = UserResource::collection($users->items());

        if ($users->data->isEmpty()) {
            return $this->response($users, "not found", Response::HTTP_NOT_FOUND);
        }

        return $this->response($users, "found", Response::HTTP_OK);
    }

    /**
     * get employee by id
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function show(ShowEmployeeRequest $request, $id)
    {
        $user = User::with('employee.position.division', 'employee.status')->has('employee')->findOrFail($id);

        return $this->response(
            new UserResource($user),
            'found',
            Response::HTTP_OK,
        );
    }

    /**
     * Registers an employee
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterEmployeeRequest $request)
    {
        $data = collect($request->validated());

        $isActive = strtotime($data->get('entry_date')) > strtotime(date('Y-m-d')) ? false : true;

        try {
            DB::beginTransaction();

            $user = User::create([
                'uuid' => User::newUuid(),
                'first_name' => $data->get('first_name'),
                'last_name' => $data->get('last_name'),
                'email' => $data->get('email'),
                'password' => Hash::make($data->get('password')),
                'religion' => $data->get('religion'),
                'phone' => $data->get('phone'),
                'address' => $data->get('address'),
                'birth_date' => $data->get('birth_date'),
                'social_number' => $data->get('social_number'),
            ]);

            $user->employee()->create([ // user_id uda auto
                'position_id' => $data->get('position_id'),
                'employee_status_id' => $data->get('employee_status_id'),
                'entry_date' => $data->get('entry_date'),
                'is_active' => $isActive,
            ]);

            $user = $user->loadMissing('employee');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            // return $this->errResponse($request, "EmployeeController register, failure in transaction");
        }

        $imgFile = $request->file('img');

        if ($imgFile) {
            Log::debug('saving image after registering employee..');
            $imgFileName = User::getImgFileName($user->id, $user->uuid, $imgFile->extension());

            $uploadStatus = $imgFile->storeAs(User::IMG_PATH, $imgFileName);
            if (!$uploadStatus) {
                // storage error
                return $this->errResponse($request, 'EmployeeController register, storage fail when saving img');
            }

            $user->img = $imgFileName;
            if (!$user->save()) {
                // database error
                return $this->errResponse($request, "EmployeeController register, couldn't save img to db");
            }
        }

        return $this->response(
            new UserResource($user),
            "employee created",
            Response::HTTP_CREATED
        );
    }

    /**
     * updates an employee data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateEmployeeRequest $request)
    {
        $data = collect($request->validated());

        $userID = $data->get('id');

        try {
            DB::beginTransaction();

            Log::debug("updating user data... (employee update)");
            $user = User::with('employee')->findorFail($userID);
            $user->fill([
                'first_name' => $data->get('first_name'),
                'last_name' => $data->get('last_name'),
                'email' => $data->get('email'),
                'password' => Hash::make($data->get('password')),
                'religion' => $data->get('religion'),
                'phone' => $data->get('phone'),
                'address' => $data->get('address'),
                'birth_date' => $data->get('birth_date'),
                'social_number' => $data->get('social_number'),
            ])->save();

            Log::debug("updating employee data... (employee update)");
            $user->employee->fill([
                'position_id' => $data->get('position_id'),
                'employee_status_id' => $data->get('employee_status_id'),
                'is_active' => $data->get('is_active'),
                'entry_date' => $data->get('entry_date'),
            ])->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this->response(new UserResource($user), "employee data updated", Response::HTTP_OK);
    }

    public function destroy(Request $request, $id)
    {
        $status = Employee::findOrFail($id)->delete();
        if (!$status) {
            return $this->errResponse($request, 'failed to delee employee, db failure');
        }

        return $this->response(null, 'delete success', Response::HTTP_OK);
    }
}
