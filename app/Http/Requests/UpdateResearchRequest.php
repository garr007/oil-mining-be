<?php

namespace App\Http\Requests;

use App\Models\Research;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdateResearchRequest extends FormRequest
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
            'id' => 'required|' . Research::RULES['id'] . '|exists:research,id',

            'research_category_id' => Research::RULES['research_category_id'],
            'code' => 'required|' . Research::RULES['code'], //
            'name' => 'required|' . Research::RULES['name'], //
            'description' => 'required|' . Research::RULES['description'], //
            'start_date' => Research::RULES['start_date'],
            'due_date' => Research::RULES['due_date'],
            'status' => 'required|' . Research::RULES['status'], //
            // 'doc' => Research::RULES['doc'], // *doc diupdate pada beda endpoint!!
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->response($validator->getMessageBag(), "validation error", Response::HTTP_BAD_REQUEST)
        );
    }
}
