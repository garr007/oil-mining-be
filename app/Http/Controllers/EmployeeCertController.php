<?php

namespace App\Http\Controllers;

use App\Http\Requests\DownloadEmployeeCertRequest;
use App\Http\Requests\ShowByUserIDEmployeeCertRequest;
use App\Http\Requests\ShowEmployeeCertRequest;
use App\Http\Requests\StoreEmployeeCertRequest;
use App\Http\Requests\UpdateEmployeeCertRequest;
use App\Http\Requests\UpdateFileEmployeeCertRequest;
use App\Http\Resources\EmployeeCertResource;
use App\Models\EmployeeCert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToRetrieveMetadata;
use Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmployeeCertController extends Controller
{

    /**
     * Display a resource by id.
     */
    public function show(ShowEmployeeCertRequest $request, $id)
    {
        return $this->response(
            new EmployeeCertResource(EmployeeCert::findOrFail($id)),
            'found',
            Response::HTTP_OK,
        );
    }

    /**
     * Display a resource by user's id.
     */
    public function showByUser(ShowByUserIDEmployeeCertRequest $request, $id)
    {
        //'page' handled by laravel
        $perPage = $request->input('per_page', 20); //get
        $perPage = $perPage > 20 ? 20 : $perPage; //set max

        Log::debug('searching for emp_certs with pagination');
        $certs = EmployeeCert::where('employee_id', '=', $id)->paginate($perPage);


        $certs->data = EmployeeCertResource::collection($certs->items());

        if ($certs->data->isEmpty()) {
            $this->response($certs, "not found", Response::HTTP_NOT_FOUND);
        }

        return $this->response($certs, "success", Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreEmployeeCertRequest $request)
    {
        $data = $request->validated();

        $file = $request->file('cert');
        $certUuid = EmployeeCert::newUuid();
        $fileName = EmployeeCert::getFileName($data['employee_id'], $certUuid, $file->extension());

        $uploadStatus = $file->storeAs(EmployeeCert::FILE_PATH, $fileName);

        if (!$uploadStatus) {
            // error happened?
            return $this->errResponse($request, "failed to store employee certificate");
        }

        $cert = EmployeeCert::create([
            'uuid' => $certUuid,
            'employee_id' => $data['employee_id'],
            'code' => $data['code'],
            'date' => $data['date'],
            'exp_date' => $data['exp_date'],
            'type' => $data['type'],
            'cert' => $fileName,
        ]);
        if (!$cert) {
            Storage::delete(EmployeeCert::FILE_PATH . "/$fileName");
        }

        return $this->response(
            new EmployeeCertResource($cert),
            'certificate created',
            Response::HTTP_CREATED,
        );
    }

    /**
     * (download) the specified resource.
     * 
     * @return 
     */
    public function download(DownloadEmployeeCertRequest $request, $fileName)
    {
        try {
            $file = Storage::download(EmployeeCert::FILE_PATH . "/$fileName");
        } catch (\Exception $e) {
            if ($e instanceof UnableToRetrieveMetadata) {
                throw new NotFoundHttpException('file not found');
            }
            throw $e;
        }
        return $file;
    }

    /**
     * Update the specified resource in storage.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateEmployeeCertRequest $request)
    {
        $data = $request->validated();

        EmployeeCert::where('id', $data['id'])->update($data);
        $cert = EmployeeCert::where('id', $data['id'])->first();

        return $this->response(
            new EmployeeCertResource($cert),
            'update success',
            Response::HTTP_OK,
        );
    }

    /**
     * Update the specified resource in storage.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateFile(UpdateFileEmployeeCertRequest $request)
    {
        $data = $request->validated();

        $file = $request->file("cert");

        $cert = EmployeeCert::where("id", $data['id'])->first();
        $fileName = EmployeeCert::getFileName($cert->employee_id, $cert->uuid, $file->extension());

        $uploadStatus = $file->storeAs(EmployeeCert::FILE_PATH, $fileName);

        if (!$uploadStatus) {
            return $this->errResponse($request, 'storage fail, when updating emp cert file');
        }

        try {
            DB::beginTransaction();

            $cert->cert = $fileName;
            $cert->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Storage::delete(EmployeeCert::FILE_PATH . "/$fileName");
            throw $e;
        }

        return $this->response(new EmployeeCertResource($cert), "update success", Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $cert = EmployeeCert::where("id", $id)->firstOrFail();

        $fileName = $cert->cert;
        $status = $cert->delete($id);
        if (!$status) {
            return $this->errResponse($request, 'failed to delete employeecert, db failure');
        }

        $status = Storage::delete(EmployeeCert::FILE_PATH . "/$fileName");
        if (!$status) {
            return $this->errResponse($request, 'failed to delete employeecert, db failure');
        }

        return $this->response(null, 'delete success', Response::HTTP_OK);
    }

}
