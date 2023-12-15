<?php

namespace App\Http\Controllers;

use App\Models\PersonalData;
use Illuminate\Http\Request;
use App\Http\Resources\PersonalDataResource;
use Symfony\Component\HttpFoundation\Response;

class PersonalDataController extends Controller
{
    public function index()
    {
        $posts = PersonalData::with('employee:id,name')->get();
        return PersonalDataResource::collection($posts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'Lokasi_Pekerjaan' => 'required|max:255',
            'Kontak_Darurat' => 'required|max:14',
        ]);


        $post = PersonalData::create($request->all());

        return $this->response(
            new PersonalDataResource($post),
            "created",
            Response::HTTP_CREATED
        );
    }

    // public function update(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'Lokasi_Pekerjaan' => 'required|max:255',
    //         'Kontak_Darurat' => 'required|max:14',
    //     ]);

    //     $post = PersonalData::findOrFail($id);
    //     $post->update($request->all());

    //     return $this->response(
    //         new PersonalDataResource($post),
    //         "updated",
    //         Response::HTTP_OK
    //     );
    // }

    // public function destroy($id)
    // {

    //     $post = PersonalData::findOrFail($id);
    //     $post->delete();

    //     return $this->response(
    //         new PersonalDataResource($post),
    //         "deleted",
    //         Response::HTTP_OK
    //     );
    // }
}
