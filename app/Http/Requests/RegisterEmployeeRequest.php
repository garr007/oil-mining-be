<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class RegisterEmployeeRequest extends FormRequest
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
            "first_name" => User::RULES['first_name'],
            "last_name" => User::RULES['last_name'],
            "email" => User::RULES['email_register'],
            "password" => User::RULES['password'],
            "religion" => Employee::RULES['religion'],
            "phone" => Employee::RULES['phone'],
            "address" => Employee::RULES['address'],
            "birth_date" => Employee::RULES['birth_date'],
            "social_number" => Employee::RULES['social_number'],
            "img" => Employee::RULES['img'],
            'employee_status_id' => Employee::RULES['employee_status_id'],
            'entry_date' => Employee::RULES['entry_date'],
            // "division_id" => Employee::RULES['division_id'],
            "position_id" => Employee::RULES['position_id'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->response($validator->getMessageBag(), "validation error", Response::HTTP_BAD_REQUEST)
        );
    }
}
