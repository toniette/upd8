<?php

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'document' => ['required', 'cpf'],
            'birthdate' => ['required', 'date'],
            'gender' => ['required', Rule::in(Customer::AVAILABLE_GENDERS)],
            'address' => ['required'],
            'address.street' => ['required', 'string'],
            'address.number' => ['nullable', 'string'],
            'address.complement' => ['nullable', 'string'],
            'address.district' => ['required', 'string'],
            'address.city' => ['required', 'string'],
            'address.state' => ['required', 'string'],
            'address.country' => ['nullable', 'string'],
            'address.zip_code' => ['required', 'string'],
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'Nome',
            'last_name' => 'Sobrenome',
            'document' => 'CPF',
            'birthdate' => 'Data de Nascimento',
            'gender' => 'Sexo',
            'address' => 'Endereço',
            'address.street' => 'Logradouro',
            'address.number' => 'Número',
            'address.complement' => 'Complemento',
            'address.district' => 'Bairro',
            'address.city' => 'Cidade',
            'address.state' => 'Estado',
            'address.country' => 'País',
            'address.zip_code' => 'CEP',
        ];
    }

    public function messages()
    {
        return [
            'birthdate' => 'Este campo é obrigatório.',
        ];
    }
}
