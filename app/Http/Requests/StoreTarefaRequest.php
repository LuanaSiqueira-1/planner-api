<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTarefaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categoria_id' => [
                'nullable',
                'integer',
                'exists:categorias,id',
            ],
            'descricao' => [
                'required',
                'string',
                'max:255',
            ],
            'status' => [
                'sometimes',
                Rule::in([
                    'CUMPRIDA',
                    'PARCIAL',
                    'NAO_CUMPRIDA',
                ]),
            ],
            'data' => [
                'required',
                'date',
            ],
            'hora_inicio' => [
                'required',
                'date_format:H:i',
            ],
            'hora_fim' => [
                'required',
                'date_format:H:i',
                'after:hora_inicio',
            ],
            'turno' => [
                'required',
                Rule::in([
                    'MANHA',
                    'TARDE',
                    'NOITE',
                ]),
            ],
            'prioridade' => [
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

            'hora_inicio.required' => 'A hora de início é obrigatória.',
            'hora_inicio.date_format' => 'A hora de início deve estar no formato HH:MM.',

            'hora_fim.required' => 'A hora de fim é obrigatória.',
            'hora_fim.date_format' => 'A hora de fim deve estar no formato HH:MM.',
            'hora_fim.after' => 'A hora de fim deve ser posterior à hora de início.',

            'turno.in' => 'O turno informado é inválido.',
            'prioridade.in' => 'A prioridade informada é inválida.',
            'status.in' => 'O status informado é inválido.',
        ];
    }
}