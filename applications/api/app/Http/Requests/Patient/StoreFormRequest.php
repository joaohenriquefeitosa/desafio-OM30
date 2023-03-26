<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Elegant\Sanitizer\Laravel\SanitizesInput;

class StoreFormRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'birth_date' => ['required', 'string'],
            'cpf' => ['required', 'numeric', 'digits:11', 'unique:patients,cpf'],
            'cns' => ['required', 'numeric', 'digits:15', 'unique:patients,cns'],
            'mother_name' => ['required', 'string', 'min:2', 'max:150'],
            'zipcode' => ['required', 'digits:8'],
            'address' => ['required', 'string', 'min:2', 'max:255'],
            'number' => ['required', 'string', 'min:1', 'max:50'],
            'complement' => ['required', 'string', 'min:2', 'max:150'],
            'neighborhood' => ['required', 'string', 'min:2', 'max:150'],
            'city' => ['required', 'string', 'min:2', 'max:150'],
            'state' => ['required', 'string', 'min:2', 'max:2'],
            'picture' => ['nullable'],
        ];
    }
}
