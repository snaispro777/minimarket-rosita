<?php

namespace App\Policies;

use App\Models\Diapositiva;
use App\Models\User;

class DiapositivaPolicy
{
    public function create(User $user): bool
    {
        return $user->esAdmin();
    }

    public function update(User $user, Diapositiva $diapositiva): bool
    {
        return $user->esAdmin();
    }

    public function delete(User $user, Diapositiva $diapositiva): bool
    {
        return $user->esAdmin();
    }
}
