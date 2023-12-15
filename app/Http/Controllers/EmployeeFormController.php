<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeFormResource;
use App\Models\EmployeeForm;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeFormController extends Controller
{
    public function index($id)
    {
        $posts = EmployeeForm::findOrFail($id)->load('employee:id,Lokasi_Pekerjaan,employee_id');
        return new EmployeeFormResource($posts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Riwayat_Penyakit_Jantung' => 'required',
            'Penurunan_Kinerja_Fisik' => 'required',
            'Gangguan_Pendengaran' => 'required',
            'Pelindung_Pendengaran' => 'required',
            'Kecelakaan_Keamanan' => 'required',
            'personal_id' => 'required|exists:personal_data,id'
        ]);


        $post = EmployeeForm::create($request->all());
        $post->save();
        $post->load('employee:id,Lokasi_Pekerjaan,employee_id');

        return $this->response(
            new EmployeeFormResource($post),
            "created",
            Response::HTTP_CREATED
        );
    }
}
