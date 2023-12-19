<?php

namespace App\Http\Controllers;

use App\Models\OilTask;
use Illuminate\Http\Request;
use App\Http\Resources\OilTaskResource;
use Symfony\Component\HttpFoundation\Response;

class OilTaskController extends Controller
{
    public function index()
    {
        $posts = OilTask::with('employee:id,name')->get();
        return OilTaskResource::collection($posts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Judul' => 'required|max:45',
            'Isi' => 'required',
            'employee_id' => 'required'
        ]);


        $post = OilTask::create($request->all());

        return $this->response(
            new OilTaskResource($post),
            "created",
            Response::HTTP_CREATED
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'Judul' => 'required|max:45',
            'Isi' => 'required',
        ]);

        $post = OilTask::findOrFail($id);
        $post->update($request->all());

        return $this->response(
            new OilTaskResource($post),
            "updated",
            Response::HTTP_OK
        );
    }

    public function destroy($id)
    {

        $post = OilTask::findOrFail($id);
        $post->delete();

        return $this->response(
            new OilTaskResource($post),
            "deleted",
            Response::HTTP_OK
        );
    }
}
