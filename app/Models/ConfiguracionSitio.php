<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionSitio extends Model
{
    use HasFactory;

    protected $table = 'configuracion_sitio';

    protected $fillable = [
        'logo',
    ];

    public function getLogoUrlAttribute(): string
    {
        if (!$this->logo) {
            return asset('rosita.jpg');
        }

        return asset('storage/'.$this->logo);
    }
}
