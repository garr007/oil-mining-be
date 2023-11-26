<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ShowEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $payload = auth()->payload();
        if ($payload['is_admin'] || $payload['is_manager']) {
            return true;
        }

        $id = request()->route('id');
        $user = auth()->user();

        if ($user->id != $id) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->response($validator->getMessageBag(), "validation error", Response::HTTP_BAD_REQUEST)
        );
    }
    protected function failedAuthorization()
    {
        throw new UnauthorizedHttpException('');
    }
}