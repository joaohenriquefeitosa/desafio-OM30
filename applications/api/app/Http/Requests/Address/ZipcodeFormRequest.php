<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;
use Elegant\Sanitizer\Laravel\SanitizesInput;

class ZipcodeFormRequest extends FormRequest
{
    use SanitizesInput;

    public function filters()
    {
        return [
            'zipcode' => 'digit',
        ];
    }
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'zipcode' => ['nullable', 'digits:8'],
        ];
    }
}
