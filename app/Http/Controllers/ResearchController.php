<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResearchRequest;
use App\Http\Requests\UpdateDocResearchRequest;
use App\Http\Requests\UpdateResearchRequest;
use App\Http\Resources\ResearchResource;
use App\Models\Research;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //'page' handled by laravel
        $perPage = $request->input('per_page', 20); //get
        $perPage = $perPage > 20 ? 20 : $perPage; //set max

        $res = Research::with('category')->paginate($perPage);
        $res->data = ResearchResource::collection($res);

        return $this->response(
            $res,
            "found",
            Response::HTTP_OK,
        );
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreResearchRequest $request)
    {
        $data = $request->validated();

        $file = $request->file('doc');
        $uuid = Research::newUuid();
        $data['uuid'] = $uuid;

        // if uploaded, then create
        if ($file) {
            $fileName = "$uuid." . $file->extension();

            $uploadStatus = $file->storeAs(Research::DOC_FILE_PATH, $fileName);

            if (!$uploadStatus) {
                return $this->errResponse($request, "failed to store research doc");
            }
            $data['doc'] = $fileName;
        }

        try {
            DB::beginTransaction();
            $res = Research::create($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            if ($file) {
                Storage::delete(Research::DOC_FILE_PATH . "/$fileName");
            }
            throw $e;
        }

        $res = $res->loadMissing('category');

        return $this->response(
            new ResearchResource($res),
            'reserach data created',
            Response::HTTP_CREATED,
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $res = Research::where("id", $id)->firstOrFail();

        $fileName = $res->doc;
        $status = $res->delete($id);
        if (!$status) {
            return $this->errResponse($request, 'failed to delete research data, db failure');
        }

        if ($fileName) {
            $status = Storage::delete(Research::DOC_FILE_PATH . "/$fileName");
            if (!$status) {
                return $this->errResponse($request, 'failed to delete research doc, storage failure');
            }
        }

        return $this->response(null, 'delete success', Response::HTTP_OK);
    }

    /**
     * (download) the specified resource.
     */
    public function download(Request $request, $fileName)
    {
        try {
            $file = Storage::download(Research::DOC_FILE_PATH . "/$fileName");
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
    public function update(UpdateResearchRequest $request)
    {
        $data = $request->validated();

        Research::where('id', $data['id'])->update($data);
        $res = Research::where('id', $data['id'])->first();

        return $this->response(
            new ResearchResource($res),
            'update success',
            Response::HTTP_OK,
        );
    }

    /**
     * Update the research doc.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDoc(UpdateDocResearchRequest $request)
    {
        $data = $request->validated();

        // finding res
        $res = Research::findOrFail($data['id']);
        $file = $request->file('doc'); // uploaded file

        // deleting the old one
        $oldFileName = $res->doc;
        if ($oldFileName) {
            Storage::delete(Research::DOC_FILE_PATH . "/$oldFileName");
        }

        $fileName = Research::getDocFileName($res->uuid, $file->extension());

        // uploading res doc
        $uploadStatus = $file->storeAs(Research::DOC_FILE_PATH, $fileName);
        if (!$uploadStatus) {
            // storage error
            return $this->errResponse($request, 'ResearchController updateDoc, storage fail when saving doc');
        }

        // updating doc res (db)
        $res->doc = $fileName;
        if (!$res->save()) {
            Storage::delete(Research::DOC_FILE_PATH . "/$fileName");
            // database error
            return $this->errResponse($request, "ResearchController updateDoc, couldn't update res doc to db");
        }

        return $this->response(
            ['doc' => Research::getDownloadLink($fileName)],
            'doc update success',
            Response::HTTP_OK
        );
    }
}
