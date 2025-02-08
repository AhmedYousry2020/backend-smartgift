<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;
use App\Http\Traits\HttpResponsesTrait;

class SignUpRequest extends FormRequest
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
            'first_name'            => ['required','string'],
            'last_name'             => ['required','string'],
            'phone'                 => ['required','regex:/^([0-9\s\-\+\(\)]*)$/','max:20','min:7','unique:users,phone'],
            'accepted_terms'        => ['required'],
            'device_token'=>'nullable|string|max:255',
            'device_type'=>'nullable|string|max:255|in:web,android,ios',
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException($this->errors(__($validator->errors()->first()), $validator->errors()->toArray()));
    }
}
