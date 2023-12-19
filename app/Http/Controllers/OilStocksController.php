<?php

namespace App\Http\Controllers;

use App\Models\OilAssets;
use App\Models\OilStocks;
use Illuminate\Http\Request;
use App\Http\Resources\OilStocksResource;
use Symfony\Component\HttpFoundation\Response;

class OilStocksController extends Controller
{
    public function index()
    {
        return $this->response(
            OilStocksResource::collection(OilStocks::all()),
            "found",
            Response::HTTP_OK,
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Jenis_Minyak' => 'required|max:255',
            'Jumlah' => 'required',
            'Tanggal_Masuk' => 'required',
            'Tanggal_Keluar' => 'required',
            'Lokasi_Penyimpanan' => 'required',
        ]);

        $post = OilStocks::create($request->all());

        return $this->response(
            new OilStocksResource($post),
            "created",
            Response::HTTP_CREATED
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'Jenis_Minyak' => 'required|max:45',
            'Jumlah' => 'required',
        ]);

        $post = OilStocks::findOrFail($id);
        $post->update($request->all());

        return $this->response(
            new OilStocksResource($post),
            "updated",
            Response::HTTP_OK
        );
    }

    public function destroy($id)
    {

        $post = OilStocks::findOrFail($id);
        $post->delete();

        return $this->response(
            new OilStocksResource($post),
            "deleted",
            Response::HTTP_OK
        );
    }
}
