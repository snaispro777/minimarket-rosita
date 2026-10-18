<?php

namespace App\Http\Requests\Admin;

use App\Models\Diapositiva;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreDiapositivaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Diapositiva::class);
    }

    public function rules(): array
    {
        return [
            'imagen' => ['required', File::image()->max('5mb')],
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
