<?php

namespace App\Http\Controllers;

use App\Http\Resources\OilAssetsResource;
use App\Models\OilAssets;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OilAssetsController extends Controller
{
    public function index()
    {
        return $this->response(
            OilAssetsResource::collection(OilAssets::all()),
            "found",
            Response::HTTP_OK,
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Nama_Aset' => 'required|max:255',
            'Jenis_Aset' => 'required|max:50',
            'Status_Aset' => 'required|max:20',
            'Riwayat_Status' => 'required',
        ]);

        $post = OilAssets::create($request->all());

        return $this->response(
            new OilAssetsResource($post),
            "created",
            Response::HTTP_CREATED
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'Nama_Aset' => 'required|max:255',
            'Jenis_Aset' => 'required|max:50',
        ]);

        $post = OilAssets::findOrFail($id);
        $post->update($request->all());

        return $this->response(
            new OilAssetsResource($post),
            "updated",
            Response::HTTP_OK
        );
    }

    public function destroy($id)
    {

        $post = OilAssets::findOrFail($id);
        $post->delete();

        return $this->response(
            new OilAssetsResource($post),
            "deleted",
            Response::HTTP_OK
        );
    }
}
