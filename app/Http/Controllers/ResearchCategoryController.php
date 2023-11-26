<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResearchCategoryRequest;
use App\Http\Requests\UpdateResearchCategoryRequest;
use App\Http\Resources\ResearchCategoryResource;
use App\Models\ResearchCategory;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class ResearchCategoryController extends Controller
{
    public function store(StoreResearchCategoryRequest $request)
    {
        $data = $request->validated();

        $cat = ResearchCategory::create($data);

        return $this->response(
            new ResearchCategoryResource($cat),
            'created',
            Response::HTTP_CREATED
        );
    }

    public function index()
    {
        return $this->response(
            ResearchCategoryResource::collection(ResearchCategory::all()),
            'found',
        );
    }

    public function destroy(Request $request, $id)
    {
        $cat = ResearchCategory::findOrFail($id);
        $status = $cat->delete($id);
        if (!$status) {
            return $this->errResponse($request, 'failed to delete employeecert, db failure');
        }

        return $this->response(null, 'delete success');
    }

    public function update(UpdateResearchCategoryRequest $request)
    {
        $data = $request->validated();

        $cat = ResearchCategory::findOrFail($data['id']);

        $cat->fill($data)->save();

        return $this->response(
            new ResearchCategoryResource($cat),
            'updated',
            Response::HTTP_OK
        );
    }
}
