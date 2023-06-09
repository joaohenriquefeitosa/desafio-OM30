<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Elegant\Sanitizer\Laravel\SanitizesInput;

class UpdateFormRequest extends FormRequest
{
    use SanitizesInput;

    public function filters()
    {
        return [
            'zipcode' => 'digit',
            'cpf' => 'digit',
            'picture' => 'cast:array',
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
            'name' => ['nullable', 'string', 'min:2', 'max:150'],
            'birth_date' => ['nullable', 'string'],
            'cpf' => ['nullable', 'numeric', 'digits:11', 'unique:patients,cpf'.$this->patient],
            'cns' => ['nullable', 'numeric', 'digits:15', 'unique:patients,cns'.$this->patient],
            'mother_name' => ['nullable', 'string', 'min:2', 'max:150'],
            'picture' => ['nullable'],
            'zipcode' => ['nullable', 'digits:8'],
            'address' => ['nullable', 'string', 'min:2', 'max:255'],
            'number' => ['nullable', 'string', 'min:1', 'max:50'],
            'complement' => ['nullable', 'string', 'min:2', 'max:150'],
            'neighborhood' => ['nullable', 'string', 'min:2', 'max:150'],
            'city' => ['nullable', 'string', 'min:2', 'max:150'],
            'state' => ['nullable', 'string', 'min:2', 'max:2'],
        ];
    }
}
