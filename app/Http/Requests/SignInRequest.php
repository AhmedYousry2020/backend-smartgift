<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Traits\HttpResponsesTrait;
use Illuminate\Validation\Rule;

class SignInRequest extends FormRequest
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
            'phone'    => ['required','string','exists:users,phone'],
        ];
    }

    public function messages()
    {
        return [
            'phone.exists' =>  __('auth.failed'),
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException($this->errors('Validation errors', $validator->errors()->toArray()));

    }
}
