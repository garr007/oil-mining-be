<?php

namespace App\Http\Controllers;

use App\Models\OilTransfer;
use Illuminate\Http\Request;
use App\Http\Resources\OilTransferResource;
use Symfony\Component\HttpFoundation\Response;
use App\Models\OilAssets;

class OilTransferController extends Controller
{

    public function index()
    {
        return $this->response(
            OilTransferResource::collection(OilTransfer::all()),
            "found",
            Response::HTTP_OK,
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Tanggal_Pindah' => 'required',
            'Lokasi_Pindah' => 'required|max:255',
            'Lokasi_Tujuan' => 'required|max:255',
            'Keterangan' => 'required',
            'Nama_Aset' => 'required'
        ]);

        $post = OilTransfer::create($request->all());

        return $this->response(
            new OilTransferResource($post),
            "created",
            Response::HTTP_CREATED
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'Tanggal_Pindah' => 'required',
            'Lokasi_Pindah' => 'required|max:255',
            'Lokasi_Tujuan' => 'required|max:255',
            'Keterangan' => 'required'
        ]);

        $post = OilTransfer::findOrFail($id);
        $post->update($request->all());

        return $this->response(
            new OilTransferResource($post),
            "updated",
            Response::HTTP_OK
        );
    }

    public function destroy($id)
    {

        $post = OilTransfer::findOrFail($id);
        $post->delete();

        return $this->response(
            new OilTransferResource($post),
            "deleted",
            Response::HTTP_OK
        );
    }
}
