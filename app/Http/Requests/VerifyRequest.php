<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;
use App\Http\Traits\HttpResponsesTrait;

class VerifyRequest extends FormRequest
{

    use HttpResponsesTrait;


    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected $stopOnFirstFailure = false;

    public function rules()
    {
        return [
            'code'    => ['required','numeric',  'min:4'],
            'phone'    => ['required','string','max:255','exists:users,phone'],
        ];
    }
    public function failedValidation(Validator $validator){
        throw new HttpResponseException($this->errors('Validation errors', $validator->errors()->toArray()));

    }
}
