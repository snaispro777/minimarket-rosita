<?php

namespace App\Http\Requests\Admin;

use App\Models\Diapositiva;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateDiapositivaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('diapositiva'));
    }

    public function rules(): array
    {
        return [
            'imagen' => ['nullable', File::image()->max('5mb')],
            'texto_alt' => ['required', 'string', 'max:150'],
        ];
    }

    public function attributes(): array
    {
        return [
            'texto_alt' => 'texto alternativo',
        ];
    }
}
