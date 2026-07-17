<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => [
                'required',
                'string',
                'max:255',
            ],
            'cor' => [
                'required',
                'string',
                'max:20',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome da categoria é obrigatório.',
            'nome.string' => 'O nome da categoria deve ser um texto.',
            'nome.max' => 'O nome da categoria deve possuir no máximo 255 caracteres.',

            'cor.required' => 'A cor da categoria é obrigatória.',
            'cor.string' => 'A cor da categoria deve ser um texto.',
            'cor.max' => 'A cor da categoria deve possuir no máximo 20 caracteres.',
        ];
    }
}