<?php

namespace App\Http\Requests;

use App\Models\EmployeeCert;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdateEmployeeCertRequest extends FormRequest
{
    use ApiResponser;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'id' => 'required|exists:employee_certs,id',
            'employee_id' => EmployeeCert::RULES['employee_id'],
            'date' => EmployeeCert::RULES['date'],
            'exp_date' => EmployeeCert::RULES['exp_date'],
            'type' => EmployeeCert::RULES['type']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->response($validator->getMessageBag(), "validation error", Response::HTTP_BAD_REQUEST)
        );
    }
}
