<?php

namespace App\Http\Requests;

use App\Http\Traits\HasSaudiNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;
use App\Http\Traits\HttpResponsesTrait;

class ReSendVerifyRequest extends FormRequest
{

    use HttpResponsesTrait, HasSaudiNumber;


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
            'phone'    => ['required','string','max:255','exists:users,phone'],
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException($this->errors('Validation errors', $validator->errors()->toArray()));
    }
}
