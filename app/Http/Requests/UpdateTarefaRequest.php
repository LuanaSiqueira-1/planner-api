<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTarefaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categoria_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:categorias,id',
            ],
            'descricao' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'status' => [
                'sometimes',
                'required',
                Rule::in([
                    'CUMPRIDA',
                    'PARCIAL',
                    'NAO_CUMPRIDA',
                ]),
            ],
            'data' => [
                'sometimes',
                'required',
                'date',
            ],
            'hora_inicio' => [
                'sometimes',
                'required_with:hora_fim',
                'date_format:H:i',
            ],
            'hora_fim' => [
                'sometimes',
                'required_with:hora_inicio',
                'date_format:H:i',
                'after:hora_inicio',
            ],
            'turno' => [
                'sometimes',
                'required',
                Rule::in([
                    'MANHA',
                    'TARDE',
                    'NOITE',
                ]),
            ],
            'prioridade' => [
                'sometimes',
                'required',
                Rule::in([
                    'ALTA',
                    'MEDIA',
                    'BAIXA',
                ]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'categoria_id.integer' => 'O identificador da categoria deve ser um número inteiro.',
            'categoria_id.exists' => 'A categoria informada não existe.',

            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.string' => 'A descrição deve ser um texto.',
            'descricao.max' => 'A descrição deve possuir no máximo 255 caracteres.',

            'data.required' => 'A data é obrigatória.',
            'data.date' => 'A data informada é inválida.',

            'hora_inicio.required_with' => 'A hora de início é obrigatória quando a hora de fim for informada.',
            'hora_inicio.date_format' => 'A hora de início deve estar no formato HH:MM.',

            'hora_fim.required_with' => 'A hora de fim é obrigatória quando a hora de início for informada.',
            'hora_fim.date_format' => 'A hora de fim deve estar no formato HH:MM.',
            'hora_fim.after' => 'A hora de fim deve ser posterior à hora de início.',

            'turno.in' => 'O turno informado é inválido.',
            'prioridade.in' => 'A prioridade informada é inválida.',
            'status.in' => 'O status informado é inválido.',
        ];
    }
}