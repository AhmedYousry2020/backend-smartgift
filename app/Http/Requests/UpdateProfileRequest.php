<?php
    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Http\Exceptions\HttpResponseException;

    use Illuminate\Contracts\Validation\Validator;
    use App\Http\Traits\HttpResponsesTrait;
    use Illuminate\Validation\Rule;

    class UpdateProfileRequest extends FormRequest
    {
        use HttpResponsesTrait;

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
                'first_name' => 'nullable|string|max:255',
                'last_name'  => 'nullable|string|max:255',
                'address'    => 'nullable|string|max:255',
                'image'      => 'nullable|file|mimes:jpg,jpeg,png', // Add validation for the image field
            ];
        }

        public function failedValidation(Validator $validator){
            throw new HttpResponseException($this->errors('Validation errors', $validator->errors()->toArray()));

        }
    }
