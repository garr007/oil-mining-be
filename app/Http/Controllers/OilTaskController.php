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
        return $this->response(
            OilTaskResource::collection(OilTask::all()),
            "found",
            Response::HTTP_OK,
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Judul' => 'required|max:45',
            'Isi' => 'required',
        ]);


        $post = OilTask::create($request->all());

        return $this->response(
            new OilTaskResource($post),
            "created",
            Response::HTTP_CREATED
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
