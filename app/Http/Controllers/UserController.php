<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateImgUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Storage;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    /**
     * Get user's image
     *
     * @return
     */
    public function img(string $fileName)
    {

        $path = User::IMG_PATH . "/" . "$fileName";
        $img = Storage::get($path);
        if (!$img) {
            return $this->response(null, "image not found", Response::HTTP_NOT_FOUND);
        }

        return response($img, 200)->header('Content-Type', Storage::mimeType($path));
    }

    public function updateImg(UpdateImgUserRequest $request)
    {
        // only admin&manager is capable of changing user img
        // that's why i don't use auth

        $data = $request->validated();

        $imgFile = $request->file('img');

        // finding user
        $user = User::findOrFail($data['id']);
        $file = $request->file('img');

        $imgFileName = User::getImgFileName($user->id, $user->uuid, $file->extension());

        // uploading img
        Storage::delete(User::IMG_PATH . "/$user->img");
        $uploadStatus = $imgFile->storeAs(User::IMG_PATH, $imgFileName);
        if (!$uploadStatus) {
            // storage error
            return $this->errResponse($request, 'UserController imgUpdate, storage fail when saving img');
        }

        // updating user img (db)
        $user->img = $imgFileName;
        if (!$user->save()) {
            Storage::delete(User::IMG_PATH . "/$imgFileName");
            // database error
            return $this->errResponse($request, "UserController imgUpdate, couldn't save img to db");
        }

        return $this->response(
            ['img' => User::getImgLink($imgFileName)],
            'img update success',
            Response::HTTP_OK
        );
    }

    /**
     * updates user's own password
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(UpdateUserPasswordRequest $request)
    {
        $data = $request->validated();

        $user = auth()->user();
        //check if the same as old one
        if (!Hash::check($data['old_password'], $user->getAuthPassword())) {
            throw new BadRequestHttpException('wrong password');
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        return $this->response(
            null,
            "password updated",
            Response::HTTP_OK
        );
    }
}
