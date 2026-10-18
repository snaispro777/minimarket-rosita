<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diapositiva extends Model
{
    use HasFactory;

    protected $table = 'diapositivas';

    protected $fillable = [
        'imagen',
        'texto_alt',
        'orden',
    ];

    public function getImagenAttribute(?string $imagen): ?string
    {
        if (!$imagen || filter_var($imagen, FILTER_VALIDATE_URL)) {
            return $imagen;
        }

        return asset('storage/'.$imagen);
    }
}
