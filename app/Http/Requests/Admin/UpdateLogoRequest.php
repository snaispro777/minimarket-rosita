<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateLogoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->esAdmin();
    }

    public function rules(): array
    {
        return [
            'logo' => ['required', File::image()->max('5mb')],
        ];
    }
}
